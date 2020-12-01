/*
// Nota: la librería cf-aesdk.js debe ser llamada "ANTES" de esta siempre
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

// LR- Codigo fuente del contrato
// 
const contractSource = `
include "List.aes"

payable contract Comision =
    record tx = {
        idtx : int,
        suma : bool,
        monto : int,
        comision : int,
        neto  : int,
        cuenta : string }

    record card = {
        cuenta : address,
        card  : string,
        saldo: int,
        comisiones : int,
        txs : list(tx) }

    record state = { 
        cards : map(address, card) }

    entrypoint init() = { cards = {} }


    entrypoint getAccountct() : address = Contract.address

    entrypoint getBalancect() : int = Contract.balance

    entrypoint getAccountcl() : address = Call.caller

    entrypoint getBalancecl() : int = Chain.balance(Call.caller)

    entrypoint getBalanceak(direccion' : address) : int = Chain.balance(direccion')

    entrypoint getCuenta(cuenta' : address) : card = state.cards[cuenta']

    entrypoint existeCuenta(cuenta' : address) = Map.member(cuenta', state.cards)

    entrypoint getTxs() =
        let crd = getCuenta(Call.caller)
        crd.txs

    entrypoint existeTx() = 
        let cnt = existeCuenta(Call.caller)
        if(!cnt)
            false
        else
            let crd = getCuenta(Call.caller)
            if(List.is_empty(crd.txs))
                false
            else
                true


    payable stateful entrypoint enviarFondosContrato() =
        Chain.spend(Contract.address, Call.value) // Aqui hay que poner monto para que el contrato lo guarde


    stateful function cuentaExistente(sender' : address, monto' : int, comision' : int, neto': int, saldo' : int, idtx' : int, card' : string) = 
        let crd = getCuenta(sender')
        let comisiones' = crd.comisiones + comision'

        let xtx = { idtx = idtx', suma = true, monto = monto', comision = comision', neto = neto', cuenta = "" }
        let lst = crd.txs ++ [xtx]

        let crd1 = { cuenta = sender', card = card', saldo = saldo', comisiones = comisiones', txs = lst }
        put(state{ cards[sender'] = crd1 })
        true


    stateful function cuentaNueva(sender' : address, monto' : int, comision' : int, neto': int, saldo' : int, idtx' : int, card' : string) = 
        let comisiones' = comision'
        let xtx = { idtx = idtx', suma = true, monto = monto', comision = comision', neto = neto', cuenta = "" }
        let lst = [xtx]

        let crd1 = { cuenta = sender', card = card', saldo = saldo', comisiones = comisiones', txs = lst }
        put(state{ cards[Call.caller] = crd1 })
        true


    /* Función para calcular la comisión cuando no existe la cuenta */
    payable stateful entrypoint calculaComision(card' : string, idtx' : int) =
        let sender' = Call.caller
        let monto' = Call.value
        let comision' =  monto' * 3 / 100
        let neto' = monto' - comision'
        Chain.spend(sender', neto') // Aqui hay que poner monto para que el contrato lo guarde
        let saldo' : int = getBalancecl()
        if(existeTx())
            cuentaExistente(sender', monto', comision', neto', saldo', idtx', card')
        else
            cuentaNueva(sender', monto', comision', neto', saldo', idtx', card')


    payable stateful entrypoint retiraComision(idtx' : int, direccion' : address, monto' : int) =
        Chain.spend(direccion', monto') // Aqui si no se llama con monto el dinero sale del contrato
        let saldo' = getBalancecl()

        let crd = getCuenta(Call.caller)

        let comisiones' = crd.comisiones - monto'

        let xtx = { idtx = idtx', suma = false, monto = 0, comision = monto', neto = 0, cuenta = Address.to_str(direccion') }

        let lst = crd.txs ++ [xtx]

        let card1 = state.cards{ [Call.caller].saldo = saldo' }
        put(state {cards = card1})

        let card2 = state.cards{ [Call.caller].comisiones = comisiones' }
        put(state {cards = card2})

        let card3 = state.cards{ [Call.caller].txs = lst }
        put(state {cards = card3})
`;

// LR- Dirección donde fue desplegado el contrato
// 
const contractAddress = 'ct_3cCasdgETrgJKgwf979pn1DUHSj14G1HK3BZZZjXtSfn8y5j9';

/*
//Create a asynchronous read call for our smart contract
async function callStatic(func, args) {
   //Create a new contract instance that we can interact with
   const contract = await Client.getContractInstance(contractSource, {contractAddress});
   //Make a call to get data of smart contract func, with specefied arguments
   const calledGet = await contract.call(func, args, {callStatic: true}).catch(e => console.error(e));
   //Make another call to decode the data received in first call
   const decodedGet = await calledGet.decode().catch(e => console.error(e));
 
   return decodedGet;
 }
 
 //Create a asynchronous write call for our smart contract
 async function contractCall(func, args, value) {
   //Create a new contract instance that we can interact with
   const contract = await Client.getContractInstance(contractSource, {contractAddress});
   //Make a call to write smart contract func, with aeon value input
   // const calledSet = await contract.call(func, args, {amount: value}).catch(e => console.error(e));
   const calledSet = await contract.call(func, args, {amount: value}).catch(e => console.error(e));
 
   return calledSet;
 }
*/
/*
async function calculoComision(card, trxx, mont) {
   //Make the contract call to send amount and calculate comisions
   await contractCall('calculaComision', [card,trxx], mont)
   .then((resultado) => {
      console.log(resultado);
   }).catch((e) => {
      console.log(e);
   });
}
*/

async function retiroComision(trxx, acct, mont) {
   //Obtain balance from contract
   await callStatic('getBalancect', [])
   .then((balance) => {
      if(balance>mont) {
         //Make the contract call to send amount
         contractCall('retiraComision', [trxx, acct, mont], 0)
         .then((resultado) => {
            console.log(resultado);
         }).catch((e) => {
            console.log(e);
         });
      }
   }).catch((e) => {
      console.log(e);
   });
}


async function getCta() {
   //Obtain data from account
   acct = KeyPairObj.publicKey;
   await callStatic('getCuenta', [acct])
   .then((objCta) => {
      console.log(objCta);
      return objCta;
   });
   /*
      document.getElementById('sgetCta').innerHTML = JSON.parse(direccion);
   }).catch((e) => {
      console.log(e);
      document.getElementById('sgetCta').innerHTML = 'Transaction not confirmed';
   });
   // */
}


async function getAccountCt() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getAccountct', [])
   .then((direccion) => {
      console.log(direccion);
      return direccion;
   }).catch((e) => {
      console.log(e);
   });
}


async function getBalanceCt() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getBalancect', [])
   .then((balance) => {
      console.log(balance);
      return balance;
   }).catch((e) => {
      console.log(e);
   });
}


async function getAccountCl() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getAccountcl', [])
   .then((direccion) => {
      console.log(direccion);
      return direccion;
   }).catch((e) => {
      console.log(e);
   });
}


async function getBalanceCl() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getBalancecl', [])
   .then((direccion) => {
      console.log(balance);
      return balance;
   }).catch((e) => {
      console.log(e);
   });
}

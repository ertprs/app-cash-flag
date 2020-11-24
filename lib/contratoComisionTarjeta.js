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

   entrypoint getTxs() =
      let crd = getCuenta(Call.caller)
      crd.txs

   entrypoint getTx(idtx' : int) =
      let crd = getCuenta(Call.caller)
      let lst = crd.txs
      //List.get(idtx', lst)
      List.find((lst) => lst.idtx == idtx', lst)


   entrypoint existeCuenta(cuenta' : address) = Map.member(cuenta', state.cards)


   payable stateful entrypoint enviarFondosContrato() =
      Chain.spend(Contract.address, Call.value) // Aqui hay que poner monto para que el contrato lo guarde
        

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


   /* Función para calcular la comisión cuando no existe la cuenta */
   payable stateful entrypoint calculaComisionCuentaNueva(card' : string, idtx' : int) =
      let comision' = Call.value * 3 / 100

      let neto' = Call.value - comision'
      Chain.spend(Call.caller, neto') // Aqui hay que poner monto para que el contrato lo guarde
      let saldo' = getBalancecl()
      
      let comisiones' = comision'
      
      let xtx = { idtx = idtx', suma = true, monto = Call.value, comision = comision', neto = neto', cuenta = "" }

      let lst = [xtx]

      let crd = { cuenta = Call.caller, card = card', saldo = saldo', comisiones = comisiones', txs = lst }
      put(state{ cards[Call.caller] = crd })


   /* Función para calcular la comisión cuando ya existe la cuenta */
   payable stateful entrypoint calculaComisionCuentaExistente(idtx' : int) =
      let comision' = Call.value * 3 / 100

      let neto' = Call.value - comision'
      Chain.spend(Call.caller, neto') // Aqui hay que poner monto para que el contrato lo guarde
      let saldo' = getBalancecl()
      
      let crd = getCuenta(Call.caller)

      let comisiones' = crd.comisiones + comision'
      
      let xtx = { idtx = idtx', suma = true, monto = Call.value, comision = comision', neto = neto', cuenta = "" }

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
const contractAddress = 'ct_UpCZqYZAiwUMNsbqK9u17EtEXAfnSgE3wVHsgzHKHEu8C8Db5';


async function calculoComisionCuentaNueva(card, trxx, mont) {
   //Make the contract call to send amount and calculate comisions
   await funcionNoEstatica('calculaComisionCuentaNueva', [card,trxx], mont)
   .then((resultado) => {
      console.log(resultado);
   }).catch((e) => {
      console.log(e);
   });
}


async function calculoComisionCuentaExistente(trxx, mont) {
   //Make the contract call to send amount and calculate comisions
   await funcionNoEstatica('calculaComisionCuentaExistente', [trxx], mont)
   .then((resultado) => {
      console.log(resultado);
   }).catch((e) => {
      console.log(e);
   });
}


async function retiroComision(trxx, acct, mont) {
   //Obtain balance from contract
   await funcionEstatica('getBalancect', [])
   .then((balance) => {
      if(balance>mont) {
         //Make the contract call to send amount
         await funcionNoEstatica('retiraComision', [trxx, acct, mont], 0)
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
   await funcionEstatica('getCuenta', [acct])
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
   await funcionEstatica('getAccountct', [])
   .then((direccion) => {
      console.log(direccion);
      return direccion;
   }).catch((e) => {
      console.log(e);
   });
}


async function getBalanceCt() {
   //Make the contract call to send amount and calculate comisions
   await funcionEstatica('getBalancect', [])
   .then((balance) => {
      console.log(balance);
      return balance;
   }).catch((e) => {
      console.log(e);
   });
}


async function getAccountCl() {
   //Make the contract call to send amount and calculate comisions
   await funcionEstatica('getAccountcl', [])
   .then((direccion) => {
      console.log(direccion);
      return direccion;
   }).catch((e) => {
      console.log(e);
   });
}


async function getBalanceCl() {
   //Make the contract call to send amount and calculate comisions
   await funcionEstatica('getBalancecl', [])
   .then((direccion) => {
      console.log(balance);
      return balance;
   }).catch((e) => {
      console.log(e);
   });
}

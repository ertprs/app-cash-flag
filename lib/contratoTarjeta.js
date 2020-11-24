// 
// Inicialización de variables generales
// 
// Estos datos son los de la cuenta creada en el directorio local C:\Users\soluc\node_prj\ae-sdk, pass 123
// let SecretKey = 'cd9b121fc2cb7f3eec0c71a8f84e9f9b1fe4236a14b7abb207158d4c846bfe93538cfca17cd1fc14fe32598da25962886597a79ca17529e753a3e04a206f867a';
// let PublicKey = 'ak_doC6PW9dzSBvTys9ivEfo3kGXaNYWpebPBn5KAfoNtYZXAku8';

// Otra cuenta
// let SecretKey = '468950d7014efc402c6c4c2c947fcbb366a8c91e3a9a32fb0e641a60ea08a92d106e41a617350ecb7e59fd04d1f17e14edaceb741d5784abfd13f0a7b87e7694';
// let PublicKey = 'ak_8EhbUdwupnLVnotEX5wtJisMi6rFsN7sTJhkyN6HzVaTcSmaa';

// tarjeta de Lore
let dSecretKey = '93edb86c4afbdf7706b903028201a55ed4824671a5139b4d78b5ded6cb1e4dbe8dc166f999250d0600c1b7fbbf14d90ae03b860ecc72276112286ac18c263cce';
// let PublicKey = '';

// tarjeta de Luis 2
let oSecretKey = 'dfa9e54d6e94b26014e8cfd8c28faf5504a6c07a1be591c93e3069d02178d7bf85952c83fd43e9ce05c9472c1b5dc67de812fa6380d93baae97b201b32719efa';
// let PublicKey = '';

let Client = null;
let Clien2 = null;
let KeyPairObj = null;

console.log(Ae.Crypto.getAddressFromPriv(oSecretKey));
x = Ae.Crypto.getAddressFromPriv(oSecretKey);
console.log(x);
console.log(Ae.Crypto.isAddressValid(x, 'ak'));

// Inicializar todo
document.getElementById('iniciar').addEventListener('click', async function() {
   // Con la llave privada
   KeyPairObj = abrirCuentaConLlavePrivada(oSecretKey);

   // // Sin la llave privada, se crea la cuenta
   // KeyPairObj = await crearCuenta();
    
   // Se muestran las llaves
   document.getElementById('opublicKey').innerHTML = KeyPairObj.publicKey;
   document.getElementById('oprivateKey').innerHTML = KeyPairObj.privateKey;

   // se crea la instancia del Sdk necesaria para consultar balance y hacer transacciones 
   Client = await instanciaSdk( KeyPairObj.privateKey, KeyPairObj.publicKey );    

   let balance = await obtenerBalance( KeyPairObj.publicKey, Client );
   document.getElementById('obalance').innerHTML = balance;
   ///////////////////////////////////////////////////////////////////////////
   balancecontrato();
   ///////////////////////////////////////////////////////////////////////////
   KeyPairObj = abrirCuentaConLlavePrivada(dSecretKey);

   document.getElementById('dpublicKey').innerHTML = KeyPairObj.publicKey;
   document.getElementById('dprivateKey').innerHTML = KeyPairObj.privateKey;

   Clien2 = await instanciaSdk( KeyPairObj.privateKey, KeyPairObj.publicKey );    

   balance = await obtenerBalance( KeyPairObj.publicKey, Clien2 );
   document.getElementById('dbalance').innerHTML = balance;
});


// Enviar transacción
document.getElementById('enviar').addEventListener('click', async function() {
   let destAddress = document.getElementById('destinatario').value;
   // let amount = parseInt(document.getElementById('SendAmount').value.trim());
   let monto = document.getElementById('monto').value;

   // await enviarTransaccion( destAddress, monto, Client ).then((tx) => refreshBalance(tx) );
   tx = await enviarTransaccion( destAddress, monto, Client );
   // .then( (objRetorno) => { console.log("tx "+objRetorno) })
   //    .catch( (error) => { console.log(error) });
   console.log(tx);
   // .then( (tx) => {
   //    console.log("tx");
   //    console.log(tx);
   // })
});

async function refreshBalance(tx) {
   let balance = await obtenerBalance( KeyPairObj.publicKey, Client );
   document.getElementById('obalance').innerHTML = balance+ ' AE tokens';
   document.getElementById('destinatario').value = "";
   document.getElementById('monto').value = "";
   console.log(tx);
}
//////////////////////////////////////////////////////////////////////////////////

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

//Address of the meme voting smart contract on the testnet of the aeternity blockchain
const contractAddress = 'ct_UpCZqYZAiwUMNsbqK9u17EtEXAfnSgE3wVHsgzHKHEu8C8Db5';


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
 
async function balancecontrato() {
   document.getElementById('cpublicKey').innerHTML = 'Confirming transaction, please wait...';
   //Make the contract call to register the card with the newly passed values
   await callStatic('getAccountct', [])
   .then((direccion) => {
      document.getElementById('cpublicKey').innerHTML = direccion;
   }).catch((e) => {
      document.getElementById('cpublicKey').innerHTML = 'Transaction not confirmed';
   });

   document.getElementById('cbalance').innerHTML = 'Confirming transaction, please wait...';
   //Make the contract call to register the card with the newly passed values
   await callStatic('getBalancect', [])
   .then((direccion) => {
      document.getElementById('cbalance').innerHTML = direccion;
   }).catch((e) => {
      document.getElementById('cbalance').innerHTML = 'Transaction not confirmed';
   });
}

async function calcCom1() {
   let acct = document.getElementById('direccion1').value;
   let card = document.getElementById('tarjeta').value;
   let trxx = document.getElementById('trx1').value;
   let mont = document.getElementById('monto1').value;

   //Make the contract call to send amount and calculate comisions
   await contractCall('calculaComisionCuentaNueva', [card,trxx], mont)
   .then((resultado) => {
      console.log(resultado);
   }).catch((e) => {
      console.log(e);
   });
}


async function calcCom2() {
   let acct = document.getElementById('direccion2').value;
   let trxx = document.getElementById('trx2').value;
   let mont = document.getElementById('monto2').value;

   //Make the contract call to send amount and calculate comisions
   await contractCall('calculaComisionCuentaExistente', [trxx], mont)
   .then((resultado) => {
      console.log(resultado);
   }).catch((e) => {
      console.log(e);
   });
}


async function retCom() {
   let acct = document.getElementById('direccion3').value;
   let trxx = document.getElementById('trx3').value;
   let mont = document.getElementById('monto3').value;

   //Make the contract call to send amount and calculate comisions
   await contractCall('retiraComision', [trxx, acct, mont], 0)
   .then((resultado) => {
      console.log(resultado);
   }).catch((e) => {
      console.log(e);
   });
}


async function getCta() {
   //Make the contract call to send amount and calculate comisions
   document.getElementById('sgetCta').innerHTML = '';
   acct = Ae.Crypto.getAddressFromPriv(oSecretKey);
   await callStatic('getCuenta', [acct])
   .then((direccion) => {
      console.log(direccion);
      document.getElementById('sgetCta').innerHTML = JSON.stringify(direccion);
   });
   /*
      document.getElementById('sgetCta').innerHTML = JSON.parse(direccion);
   }).catch((e) => {
      console.log(e);
      document.getElementById('sgetCta').innerHTML = 'Transaction not confirmed';
   });
   // */
}


async function getAcctct() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getAccountct', [])
   .then((direccion) => {
      document.getElementById('sgetAcctct').innerHTML = direccion;
   }).catch((e) => {
      document.getElementById('sgetAcctct').innerHTML = 'Transaction not confirmed';
   });
}


async function getBalct() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getBalancect', [])
   .then((direccion) => {
      document.getElementById('sgetBalct').innerHTML = direccion;
   }).catch((e) => {
      document.getElementById('sgetBalct').innerHTML = 'Transaction not confirmed';
   });
}


async function getAcctcl() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getAccountcl', [])
   .then((direccion) => {
      document.getElementById('sgetAcctcl').innerHTML = direccion;
   }).catch((e) => {
      document.getElementById('sgetAcctcl').innerHTML = 'Transaction not confirmed';
   });
}


async function getBalcl() {
   //Make the contract call to send amount and calculate comisions
   await callStatic('getBalancecl', [])
   .then((direccion) => {
      document.getElementById('sgetBalcl').innerHTML = direccion;
   }).catch((e) => {
      document.getElementById('sgetBalcl').innerHTML = 'Transaction not confirmed';
   });
}

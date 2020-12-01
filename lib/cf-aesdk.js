/*
// Nota: las siguientes líneas debem ir en el html que llama a esta librería
//	<script src="https://unpkg.com/@aeternity/aepp-sdk@7.3.1/dist/aepp-sdk.browser-script.js"></script>
//	<script src="https://bundle.run/buffer@5.6.0"></script>
// <script src="https://unpkg.com/@aeternity/aepp-sdk@7.3.1/dist/aepp-sdk.browser-script.js"></script>
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/


// LR- Setup initial variables / Configurar variables iniciales
// 
// Se deben configurar las variables en el html que llama a las librerías
// Client: Variable que guarda la instancia del SDK y que permitirá acceder a sus funciones.
// KeyPairObj: Variable que guarda los datos de la cuenta con la que se accede al contrato
// 


// LR- Access to a Wallet / Acceder a la Wallet con su llave privada
// 
// Parámetros:
// -----------
// secretKey: LLave privada de la cuenta que se desea abrir.
// 
// Retorna:
// --------
// objeto: Objeto con dos valores: publicKey (llave pública o dirección) y privateKey (llave privada).
// 
function abrirCuentaConLlavePrivada(secretKey) {
   // LR - Convert secretKey to an ArrayBuffer / Convertir la calve privada en un ArrayBuffer
   const hexBuffer = Ae.Crypto.hexStringToByte(secretKey);

   // LR - Get to an object with secret key & private key in format UInt8Array / 
   // OBtener un objeto con la clave secreta y llave privada en formato UInt8Array
   const keyPair   = Ae.Crypto.generateKeyPairFromSecret(hexBuffer)

   // LR - Get public Key / Obtener clave pública
   const pKey = Ae.Crypto.aeEncodeKey(keyPair.publicKey);

   // LR - Return an object with public & secret key / devuelve un objeto con claves pública y privada
   return {
      publicKey: pKey,
      privateKey: secretKey
   };
}


// LR- Generate a Wallet / Generar una Wallet
// 
// Parámetros:
// -----------
// ninguno.
// 
// Retorna:
// --------
// objeto: Objeto con dos valores: publicKey (llave pública o dirección) y privateKey (llave privada).
// 
async function crearCuenta() {
   // LR - Generate keyPair and assign to secretKey-publicKey / Genera keyPair y asigna valores a secretKey-publicKey
   let { secretKey, publicKey } = Ae.Crypto.generateKeyPair(true);

   // LR - Decode vars to human-readable / decodifica variables para que puedan ser leidas
   let decodedPublicKey=Ae.Crypto.aeEncodeKey(publicKey);
   let decodedSecretKey=buffer.Buffer.from(secretKey).toString('hex');
 
   return {
      publicKey: decodedPublicKey,
      privateKey:decodedSecretKey
   };
}


// LR - Create instance of dthe SDK associate to wallet / Crear instancia del SDK asociada a la wallet
// 
// Parámetros:
// -----------
// secretKey: LLave privada de la cuenta a la que se va a conectar.
// publicKey: LLave pública o dirección de la cuenta a la que se va a conectar.
// 
// Retorna:
// --------
// objeto Instancia: Objeto con los parámetros de la conexión a la cuenta.
// 
async function instanciaSdk(secretKey,publicKey) {
   // LR - Node name / Nombre del nodo
   const NODE_URL = 'https://sdk-testnet.aepps.com';
   // const NODE_URL = 'https://sdk-mainnet.aepps.com';

   // LR - Account associated to wallet / Cuenta asociada a la wallet
   const ACCOUNT  =  Ae.MemoryAccount({
                        keypair: {
                           secretKey: secretKey,
                           publicKey: publicKey
                        }
                     });

   // LR - Create node instance / Crear una instancia del nodo
   const nodeInstance = await Ae.Node({ url: NODE_URL })

   // LR - Create the instance of the SDK associated to account / Crear instancia del SDK asociada a la cuenta
   const sdkInstance  = await Ae.Universal({
                                 compilerUrl: 'https://compiler.aepps.com',
                                 nodes: [ { name: 'test-net', instance: nodeInstance } ],
                                 accounts: [ ACCOUNT ]
                              });
                              // test-net
                              // mainnet

   // Return instance / Devuelve instancia
   return sdkInstance;
}


// LR - Get account's balance / Obtener el balance de la cuenta
// 
// Parámetros:
// -----------
// publicKey: LLave pública o dirección de la cuenta a la que se va a conectar.
// objeto Instancia: Objeto con los parámetros de la conexión a la cuenta.
// 
// Retorna:
// --------
// monto: Balance de la cuenta.
//        En caso de error, retorna cero e imprime en la cónsola el mensaje de error
// 
async function obtenerBalance(publicKey,sdkInstance) {
   try {
      // LR -  Get balance / Obtener balance
      let balance = await sdkInstance.balance(publicKey);

      balance = balance / 1000000000000000000;

      // LR -  Return balance / Devuelve balance
      return balance;
   } catch(err) {
      console.error(err);
      return 0;
   }   
}


// LR - Transfer tokens / Transferir tokens
// 
// Parámetros:
// -----------
// publicAddress: LLave pública o dirección de la cuenta destino (a la que se va a enviar la transacción).
// monto: Monto de la transacción.
// objeto Instancia: Objeto con los parámetros de la conexión a la cuenta remitente.
// 
// Retorna:
// --------
// objeto JSON que contiene:
// Hash de la transacción
// Monto
// Fee
// Dirección del destinatario
// Dirección del remitente
// 
async function enviarTransaccion(publicAddress,amount,sdkInstance){
   // LR -  Send amount, address & denomination / Envía monto, dirección y denominación
   let objRetorno = await sdkInstance.spend(amount, publicAddress, { denomination: 'ae' });

   // LR - Build JSON to return / Construir JSON para devolver
   jsonRetorno = JSON.parse('{"hash": "'+objRetorno.hash+'","amount": '+objRetorno.tx.amount+',"fee": '+objRetorno.tx.fee+',"recipient": "'+objRetorno.tx.recipientId+'","sender": "'+objRetorno.tx.senderId+'"}');

   // LR - Return JSON / Devolver JSON
   return jsonRetorno;
}

/*
// LR - Call static functions / Llamar funciones estáticas
//      Se usan para leer datos, no necesitan monto, no consumen gas
// 
// Parámetros:
// -----------
// funcion: Función ( o método del contrato a llamar).
// argumentos: Arreglo con los argumentos a pasar al método (ejemplo: [id,nombre]).
// 
// Retorna:
// --------
// objeto JSON que contiene los datos decodificados devueltos por la función o método
// 
async function funcionEstatica(func, args) {
   // LR - Create a contract instance / Crea una instancia del contrato
   const contrato = await Client.getContractInstance(contractSource, {contractAddress});

   // LR - Call to smart contract function / Llama a la función del contrato
   const datosObtenidos = await contrato.call(func, args, {callStatic: true}).catch(e => console.error(e));

   // LR - Decode data received / Decodifica los datos recibidos
   const datosDecodificados = await datosObtenidos.decode().catch(e => console.error(e));
 
   // LR - Return JSON / Devolver JSON
   return datosDecodificados;
}

 
// LR - Call static functions / Llamar funciones estáticas
//      Se usan para escribir datos, necesitan monto, consumen gas
// 
// Parámetros:
// -----------
// funcion: Función ( o método del contrato a llamar).
// argumentos: Arreglo con los argumentos a pasar al método (ejemplo: [id,nombre]).
// valor: Monto de AEttos que se enviarán al contrato para la transacción.
//
// 1 Aetto = 1/18 AE (18 decimales)
// 1 AE    = 1*10^18 Aettos (18 ceros)
// 
// Retorna:
// --------
// objeto JSON que contiene los datos decodificados devueltos por la función o método
// 
async function funcionNoEstatica(func, args, value) {
   // LR - Create a contract instance / Crea una instancia del contrato
   console.log(Client);
   const contrato = await Client.getContractInstance(contractSource, {contractAddress});

   // LR - Call to smart contract function to write data / Llama a la función del contrato para escribir datos
   const registro = await contrato.call(func, args, {amount: value}).catch(e => console.error(e));
 
   // LR - Return JSON / Devolver JSON
   return registro;
}
*/

//Create a asynchronous read call for our smart contract
async function callStatic(func, args) {
   console.log(Client);
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
   const calledSet = await contract.call(func, args, {amount: value}).catch(e => console.error(e));

   return calledSet;
}

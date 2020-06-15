import * as firebase from 'firebase/app';
import '@firebase/messaging';

 var firebaseConfig = {
  apiKey: "AIzaSyBaJPR-DDYlGFAzewj0KwGh9ZurU5c5Sgs",
  authDomain: "espace-739b6.firebaseapp.com",
  databaseURL: "https://espace-739b6.firebaseio.com",
  projectId: "espace-739b6",
  storageBucket: "espace-739b6.appspot.com",
  messagingSenderId: "480798332479",
  appId: "1:480798332479:web:79d6f11a71102b3f038a8a",
  measurementId: "G-K4C7N5M99D"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.requestPermission()
  .then( () => {
    console.log( 'Permission Granted!' );
  })
  .catch(() => {
    console.log( 'Permission Denied!' );
  });

messaging.getToken()
  .then( ( token ) => {
    console.log( [ token ] );
  })
  .catch( ( err ) => {
    console.log( err );
  });

messaging.onMessage( function( message ) {
  console.log( message );
});
// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyCgXyX0928hQsJQLxSXGWIseS3vVQlXa1o",
    authDomain: "tcli-51a44.firebaseapp.com",
    projectId: "tcli-51a44",
    storageBucket: "tcli-51a44.appspot.com",
    messagingSenderId: "628648807755",
    appId: "1:628648807755:web:e6ce2d97cf6fc14f432cce",
    measurementId: "G-YEERS8LMLK"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    console.log('test click event');
    event.waitUntil(self.clients.openWindow('#'));
});
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notification message .",
        icon: "../public/assets/images/logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});




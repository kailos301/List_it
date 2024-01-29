'use strict';

$(window).on('load', function () {
  initSW();
});

function initSW() {
  if (!'serviceWorker' in navigator) {
    // service worker does not support
    return;
  }

  if (!'PushManager' in window) {
    // push does not support
    return;
  }

  // register the service worker
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('./sw.js')
      .then(() => {
        initPush();
      })
      .catch((error) => {
      });
  }
}

function initPush() {
  if (!navigator.serviceWorker.ready) {
    return;
  }

  new Promise(function (resolve, reject) {
    const permissionResult = Notification.requestPermission().then(function (result) {
      resolve(result);
    });

    if (permissionResult) {
      permissionResult.then(resolve, reject);
    }
  }).then((permissionResult) => {
    if (permissionResult !== 'granted') {
      throw new Error('Permission not granted!');
    }

    subscribeGuest();
  });
}

function subscribeGuest() {
  navigator.serviceWorker.ready
    .then((registration) => {
      const subscribeOptions = {
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapid_public_key)
      };

      return registration.pushManager.subscribe(subscribeOptions);
    })
    .then((pushSubscription) => {
      storePushSubscription(pushSubscription);
    })
    .catch(error => {
    });
}

function urlBase64ToUint8Array(base64String) {
  var padding = '='.repeat((4 - base64String.length % 4) % 4);

  var base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  var rawData = window.atob(base64);
  var outputArray = new Uint8Array(rawData.length);

  for (var i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }

  return outputArray;
}

function storePushSubscription(pushSubscription) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch(baseURL + '/push-notification/store-endpoint', {
    method: 'POST',
    body: JSON.stringify(pushSubscription),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': token
    }
  }).then((response) => {
    return response.json();
  }).catch((error) => {
  });
}

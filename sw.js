const CACHE_STATIC_NAME = "static-v3";
const CACHE_DYNAMIC_NAME = "dynamic-v2";
const OFFLINE_URL = "offline.html";

self.addEventListener("install", function(event) {
  console.log("[Service Worker] Installing Service Worker ...", event);
  event.waitUntil(
    caches.open(CACHE_STATIC_NAME).then(function(cache) {
      console.log("[Service Worker] Precaching App Shell");
      cache.addAll([
        "/src/css/coffee.css",
        "/src/js/jquery-3.3.1.min.js",
        "/src/js/jquery.tablesorter.min.js",
        "/src/css/bootstrap.min.css",
        "/src/js/bootstrap.min.js",
        "/src/js/gmaps.js",
        "/src/js/cal-heatmap.js",
        "/src/css/cal-heatmap.css",
        "/src/js/d3.3.5.6.min.js",
        "/src/js/app.js",
        "/offline.html"
      ]);
    })
  );
});
//
self.addEventListener("activate", function(event) {
  console.log("[Service Worker] Activating Service Worker ....", event);
  event.waitUntil(
    caches.keys().then(function(keyList) {
      return Promise.all(
        keyList.map(function(key) {
          if (key !== CACHE_STATIC_NAME && key !== CACHE_DYNAMIC_NAME) {
            console.log("[Service worker] Removing old cache: ", key);
            return caches.delete(key);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

self.addEventListener("fetch", function(event) {
  // console.log('[Service Worker] Fetching something ....', event);
  event.respondWith(
    caches.match(event.request).then(function(response) {
      if (response) {
        return response;
      } else {
        return fetch(event.request)
          .then(function(res) {
            return caches.open(CACHE_DYNAMIC_NAME).then(function(cache) {
              cache.put(event.request.url, res.clone());
              return res;
            });
          })
          .catch(function(err) {
            return caches.match("/offline.html");
          });
      }
    })
  );
});

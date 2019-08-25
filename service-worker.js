/* Caching static resources */
const cache_ver = 'v1';

self.addEventListener('install', function(event) {
    event.waitUntil(
	caches.open(cache_ver).then(function(cache) {
	    return cache.addAll([
		'/src/frontend/style/DroidNaskh-Regular.woff2',
	    ]);
	}));
});

self.addEventListener('activate', function(event) {
    const cacheWhitelist = [cache_ver];
    event.waitUntil(
	caches.keys().then(function(keyList) {
	    return Promise.all(keyList.map(function(key) {
		if(cacheWhitelist.indexOf(key) === -1)
		    return caches.delete(key);
	    }));
	}));
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
	caches.match(event.request).then(function(resp) {
	    return resp || fetch(event.request).then(function(response) {
		return response;
	    });
	}).catch(function() {
	    return '';
	}));
});

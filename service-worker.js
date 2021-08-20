const ver = 'v1'

self.addEventListener('install', event => {
	event.waitUntil(
		caches.open(ver).then(cache => {
			return cache.addAll([
				'src/frontend/style/naskh.woff2',
			])
		}))
})

self.addEventListener('activate', event => {
	event.waitUntil(
		caches.keys().then(keyList => {
			return Promise.all(keyList.map(key => {
				if(key != ver)
					return caches.delete(key)
			}))
		}))
})

self.addEventListener('fetch', event => 
	event.respondWith(caches.match(event.request).then(res => 
		res || fetch(event.request).then(r => r))))

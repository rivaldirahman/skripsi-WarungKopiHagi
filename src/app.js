document.addEventListener('alpine:init', () => {
	Alpine.data('pembelian', (initialItems) => ({
		items: initialItems,
		selectedKategori: '',

		get filteredItems() {
			if (!this.selectedKategori) return this.items;
			return this.items.filter((item) => item.kategori === this.selectedKategori);
		},

		async fetchItems() {
			try {
				const res = await fetch('src/get_barang.php');
				this.items = await res.json();
				console.log('Loaded from fetch:', this.items);
			} catch (e) {
				console.error('Gagal memuat data barang:', e);
			}
		},
	}));

	Alpine.store('cart', {
		items: [],
		total: 0,
		quantity: 0,
		add(newItem) {
			// newItem.price = Number(newItem.price);
			newItem.price = Number(newItem.price);
			newItem.stok = Number(newItem.stok); // memastikan stok dalam bentuk angka
			// cek apakah ada barang yang sama di cart
			const cartItem = this.items.find((item) => item.id === newItem.id);

			if (!cartItem) {
				if (newItem.stok < 1) {
					alert(`Stok untuk ${newItem.name} habis.`);
					return;
				}
				this.items.push({ ...newItem, quantity: 1, total: newItem.price });
				this.quantity++;
				this.total += newItem.price;
			} else {
				if (cartItem.quantity + 1 > newItem.stok) {
					alert(`Stok ${newItem.name} hanya tersisa ${newItem.stok}.`);
					return;
				}
				this.items = this.items.map((item) => {
					if (item.id !== newItem.id) return item;
					item.quantity++;
					item.total = item.price * item.quantity;
					this.quantity++;
					this.total += item.price;
					return item;
				});
			}
		},
		remove(id) {
			// ambil item yang mau diremove berdasarkan id nya
			const cartItem = this.items.find((item) => item.id === id);
			// cartItem.price = Number(cartItem.price); // <-- Tambahkan ini juga
			cartItem.price = Number(cartItem.price); // <-- Tambahkan ini juga

			// Jika item lebih dari 1
			if (cartItem.quantity > 1) {
				// jika barang sudah ada, cek apakah barang beda atau sama dengan yang ada di cart
				this.items = this.items.map((item) => {
					// Jika barang berbeda
					if (item.id !== id) {
						return item;
					} else {
						// Jika barang sudah ada, kurang quantity dan totalnya
						item.quantity--;
						item.total = item.price * item.quantity;
						this.quantity--;
						this.total -= item.price;
						return item;
					}
				});
				// jika item sama dengan 1
			} else if (cartItem.quantity === 1) {
				// Jika barangnya sisa 1
				this.items = this.items.filter((item) => item.id !== id);
				this.quantity--;
				this.total -= cartItem.price;
			}
		},
	});
	// console.log(typeof newItem.price, newItem.price); // untuk debugging cek tipe datanya saat development
});

document.addEventListener('DOMContentLoaded', () => {
	const checkoutButton = document.querySelector('.checkout-button');
	const form = document.getElementById('checkoutForm');
	const nameInput = document.getElementById('namapembeli');
	const phoneInput = document.getElementById('phone');

	if (!form || !checkoutButton || !nameInput || !phoneInput) return;

	// Validasi nama hanya huruf dan spasi
	const isNameValid = (value) => /^[a-zA-Z\s]+$/.test(value);
	// Validasi nomor whatsapp hanya angka (maks 15 digit)
	const isphoneValid = (value) => /^[0-9]{1,15}$/.test(value);

	const validateForm = () => {
		const name = nameInput.value.trim();
		const phone = phoneInput.value.trim();

		const isValid = name && isNameValid(name) && phone && isphoneValid(phone);
		checkoutButton.disabled = !isValid;
		checkoutButton.classList.toggle('disabled', !isValid);
	};

	// Batasi input nomor WhatsApp hanya angka maksimal 15 digit saat diketik
	phoneInput.addEventListener('input', (e) => {
		e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 15);
		validateForm();
	});

	// Batasi input nama hanya huruf dan spasi saat diketik
	nameInput.addEventListener('input', (e) => {
		e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
		validateForm();
	});

	// Jalankan validasi saat form pertama kali dimuat
	form.addEventListener('input', validateForm);

	// Kirim data ketika tombol checkout diklik

	checkoutButton.addEventListener('click', async function (e) {
		e.preventDefault();
		const formData = new FormData(form);

		const cart = Alpine.store('cart');
		formData.append('items', JSON.stringify(cart.items));
		formData.append('total', cart.total);

		const data = new URLSearchParams(formData);

		// const objData = Object.fromEntries(data).items; // ini kalo mau konversi string ke object terus mengirim items saja, tanpa input form
		const objData = Object.fromEntries(data); // klo ini kirim data keseluruhan (items dan form)
		// console.log(objData);
		// const message = formatMessage(objData);
		// window.open('http://wa.me/6289514168181?text=' + encodeURIComponent(message));

		function tampilkanPopupSukses(data) {
			const tgl = new Date(data.tanggal);
			const formattedDate =
				('0' + tgl.getDate()).slice(-2) + '-' + ('0' + (tgl.getMonth() + 1)).slice(-2) + '-' + tgl.getFullYear() + ' ' + ('0' + tgl.getHours()).slice(-2) + ':' + ('0' + tgl.getMinutes()).slice(-2) + ':' + ('0' + tgl.getSeconds()).slice(-2);

			document.getElementById('order-id-text').textContent = data.order_id;
			document.getElementById('popup-tgl').textContent = formattedDate;
			document.getElementById('popup-nama').textContent = data.atasnama;
			document.getElementById('popup-wa').textContent = data.nowa;
			document.getElementById('popup-total').textContent = 'Rp. ' + Number(data.grandtotal || 0).toLocaleString('id-ID');

			const tbody = document.getElementById('popup-items');
			tbody.innerHTML = '';

			if (!Array.isArray(data.items)) {
				console.error('Data tidak valid:', data);
				alert('Gagal menampilkan detail transaksi. Silakan coba lagi.');
				return;
			}

			data.items.forEach((item) => {
				const tr = document.createElement('tr');
				tr.innerHTML = `
		<td style="border:1px solid #ccc; padding:6px;">${item.name}</td>
		<td style="border:1px solid #ccc; padding:6px;">Rp. ${Number(item.price || 0).toLocaleString('id-ID')}</td>
		<td style="border:1px solid #ccc; padding:6px;">${Number(item.quantity || 0)}</td>
		<td style="border:1px solid #ccc; padding:6px;">Rp. ${(Number(item.price || 0) * Number(item.quantity || 0)).toLocaleString('id-ID')}</td>
	`;
				tbody.appendChild(tr);
			});

			const popup = document.getElementById('popup-success');
			popup.style.display = 'block';

			setTimeout(() => {
				html2canvas(popup).then((canvas) => {
					const link = document.createElement('a');
					link.download = `Bukti-Pembayaran-${data.order_id}.png`;
					link.href = canvas.toDataURL();
					link.click();
				});
			}, 1000);
		}

		// Minta transaction token menggunakan ajax / fetch
		try {
			const response = await fetch('php/placeOrder.php', {
				method: 'POST',
				body: data,
			});
			//const token = await response.text();

			const result = await response.json();
			// if (result.error === 'nomeja_sudah_dipesan') {
			// 	alert('Nomeja sudah ada yang pesan');
			// 	return; // hentikan proses
			// }

			// if (result.token) {
			// 	window.snap.pay(result.token);
			// } else {
			// 	alert('Terjadi kesalahan saat memproses transaksi.');
			// }

			if (result.token) {
				// Fungsi untuk menampilkan popup dan screenshot bukti

				window.snap.pay(result.token, {
					onSuccess: function (result) {
						fetch('src/payment-success.php?order_id=' + result.order_id)
							.then((res) => res.json())
							.then((data) => tampilkanPopupSukses(data));
					},
					onPending: function (result) {
						location.reload(true);
					},
					onError: function (result) {
						alert('Terjadi kesalahan pembayaran.');
					},
					onClose: function (result) {
						alert('Kamu menutup popup tanpa menyelesaikan pembayaran.');
						location.reload(true);
					},
				});
			}

			// } else {
			// 	alert('Terjadi kesalahan saat memproses transaksi.');
			// }

			// console.log(token);
			// window.snap.pay(token);
		} catch (err) {
			console.log(err.message);
		}
	});
});

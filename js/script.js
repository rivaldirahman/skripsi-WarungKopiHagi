// Toggle class active untuk hamburger menu
const navbarNav = document.querySelector('.navbar-nav');
// ketika hamburger menu di klik
document.querySelector('#hamburger-menu').onclick = () => {
	navbarNav.classList.toggle('active');
};

// Toggle class active untuk shopping cart
const shoppingCart = document.querySelector('.shopping-cart');
document.querySelector('#shopping-cart-button').onclick = (e) => {
	shoppingCart.classList.toggle('active');
	e.preventDefault();
};

// Klik di luar elemen
const hm = document.querySelector('#hamburger-menu');
// const sb = document.querySelector('#search-button');
const sc = document.querySelector('#shopping-cart-button');

document.addEventListener('click', function (e) {
	if (!hm.contains(e.target) && !navbarNav.contains(e.target)) {
		navbarNav.classList.remove('active');
	}

	if (!sc.contains(e.target) && !shoppingCart.contains(e.target)) {
		shoppingCart.classList.remove('active');
	}
});

// Modal Box
const itemDetailModal = document.querySelector('#item-detail-modal');
const itemDetailButtons = document.querySelectorAll('.item-detail-button');

itemDetailButtons.forEach((btn) => {
	btn.onclick = (e) => {
		itemDetailModal.style.display = 'flex';
		e.preventDefault();
	};
});

// // klik tombol close modal
// document.querySelector('.modal .close-icon').onclick = (e) => {
// 	itemDetailModal.style.display = 'none';
// 	e.preventDefault();
// };

// // klik di luar modal
// window.onclick = (e) => {
// 	if (e.target === itemDetailModal) {
// 		itemDetailModal.style.display = 'none';
// 	}
// };

//  Script untuk Modal Login, Register, Forgot

// ==== MODAL LOGIN, REGISTER, RESET PASSWORD ====
window.addEventListener('DOMContentLoaded', () => {
	feather.replace();

	// Ambil semua modal
	const loginModal = document.getElementById('login-modal');
	const registerModal = document.getElementById('register-modal');
	const resetModal = document.getElementById('reset-new-password-modal');
	const userInfoModal = document.getElementById('user-info-modal');

	// Fungsi untuk membuka modal dan menutup yang lain
	function bukaModal(modalYangDibuka, modalLain = []) {
		modalYangDibuka.classList.add('show');
		modalLain.forEach((m) => m.classList.remove('show'));
		kosongkanInput();
	}

	// Fungsi untuk mengosongkan semua input setiap kali pindah modal
	function kosongkanInput() {
		const semuaInput = ['emailInput', 'passwordInput', 'registerName', 'registerEmail', 'registerPassword', 'forgotEmail', 'newPassword', 'confirmPassword'];

		semuaInput.forEach((id) => {
			const elemen = document.getElementById(id);
			if (elemen) {
				if (elemen.type === 'checkbox') elemen.checked = false;
				else elemen.value = '';
			}
		});
	}

	// Tombol login (jika belum login)
	document.getElementById('login-button')?.addEventListener('click', (e) => {
		e.preventDefault();
		bukaModal(loginModal);
	});

	// Tombol info user (jika sudah login)
	document.getElementById('user-info-button')?.addEventListener('click', (e) => {
		e.preventDefault();
		userInfoModal.classList.add('show');
	});

	// Tutup modal info user
	document.getElementById('closeUserModal')?.addEventListener('click', () => {
		userInfoModal.classList.remove('show');
	});
	window.addEventListener('click', (e) => {
		if (e.target === userInfoModal) userInfoModal.classList.remove('show');
	});

	// Tampilkan/sembunyikan password
	function togglePassword(idCheckbox, idInput) {
		const checkbox = document.getElementById(idCheckbox);
		const input = document.getElementById(idInput);
		checkbox?.addEventListener('change', () => {
			input.type = checkbox.checked ? 'text' : 'password';
		});
	}

	// Tombol-tombol navigasi antar modal
	document.getElementById('user-button')?.addEventListener('click', (e) => {
		e.preventDefault();
		bukaModal(loginModal);
	});

	document.getElementById('closeBtn')?.addEventListener('click', () => loginModal.classList.remove('show'));
	document.getElementById('closeRegister')?.addEventListener('click', () => registerModal.classList.remove('show'));
	document.getElementById('closeResetNewPassword')?.addEventListener('click', () => resetModal.classList.remove('show'));

	document.getElementById('openRegister')?.addEventListener('click', (e) => {
		e.preventDefault();
		bukaModal(registerModal, [loginModal]);
	});

	document.getElementById('openLogin')?.addEventListener('click', (e) => {
		e.preventDefault();
		bukaModal(loginModal, [registerModal, resetModal]);
	});

	document.getElementById('openReset')?.addEventListener('click', (e) => {
		e.preventDefault();
		bukaModal(resetModal, [loginModal]);
	});

	document.getElementById('backToLoginFromReset')?.addEventListener('click', (e) => {
		e.preventDefault();
		bukaModal(loginModal, [resetModal]);
	});

	// Tutup modal jika klik di luar
	window.addEventListener('click', (e) => {
		if (e.target === loginModal) loginModal.classList.remove('show');
		if (e.target === registerModal) registerModal.classList.remove('show');
		if (e.target === resetModal) resetModal.classList.remove('show');
	});

	// Tampilkan password jika dicentang
	togglePassword('showPassword', 'passwordInput');
	togglePassword('showRegisterPassword', 'registerPassword');
	togglePassword('showResetPassword', 'newPassword');
	togglePassword('showResetPassword', 'confirmPassword');
});

const signOutBtn = document.getElementById('signOutBtn');
const logoutModal = document.getElementById('logout-confirmation-modal');
const logoutYes = document.getElementById('logoutYes');
const logoutNo = document.getElementById('logoutNo');

signOutBtn?.addEventListener('click', (e) => {
	e.preventDefault();
	logoutModal.classList.add('show');
	userInfoModal?.classList.remove('show');
});

logoutYes?.addEventListener('click', () => {
	window.location.href = 'logout.php';
});

logoutNo?.addEventListener('click', () => {
	logoutModal.classList.remove('show');
});

function showRiwayatModal() {
	const userModal = document.getElementById('user-info-modal');
	if (userModal) userModal.classList.remove('show'); // pakai classList.remove

	const modal = document.getElementById('riwayatModal');
	modal.classList.add('show'); // pakai classList.add

	const tbody = document.getElementById('riwayatBody');
	tbody.innerHTML = '<tr><td colspan="6">Memuat data...</td></tr>';

	fetch('get_riwayat.php')
		.then((res) => res.json())
		.then((data) => {
			tbody.innerHTML = '';
			if (Array.isArray(data) && data.length > 0) {
				data.forEach((row) => {
					const tgl = new Date(row.tanggal);
					const formatted =
						('0' + tgl.getDate()).slice(-2) +
						'-' +
						('0' + (tgl.getMonth() + 1)).slice(-2) +
						'-' +
						tgl.getFullYear() +
						' ' +
						('0' + tgl.getHours()).slice(-2) +
						':' +
						('0' + tgl.getMinutes()).slice(-2) +
						':' +
						('0' + tgl.getSeconds()).slice(-2);
					const tr = document.createElement('tr');
					tr.innerHTML = `
						<td>${formatted}</td>
						<td>${row.order_id}</td>
						<td>${row.atasnama}</td>
						<td>${row.nowa}</td>
						<td>${row.nama_barang}</td>
						<td>Rp. ${Number(row.grandtotal).toLocaleString('id-ID')}</td>
						<td>
							<span class="${row.status === 'DIBAYAR' ? 'status-dibayar' : 'status-selesai'}">
								${row.status === 'DIBAYAR' ? 'SEDANG DIPROSES' : row.status}
							</span>
						</td>
					`;
					tbody.appendChild(tr);
				});
			} else {
				tbody.innerHTML = '<tr><td colspan="6">Tidak ada data transaksi.</td></tr>';
			}
		})
		.catch((err) => {
			tbody.innerHTML = '<tr><td colspan="6">Gagal memuat data.</td></tr>';
			console.error(err);
		});
}

function closeRiwayatModal() {
	const modal = document.getElementById('riwayatModal');
	modal.classList.remove('show'); // ganti style.display ke classList.remove

	const tbody = document.getElementById('riwayatBody');
	if (tbody) tbody.innerHTML = '';
}

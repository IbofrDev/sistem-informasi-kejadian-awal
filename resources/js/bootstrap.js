import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// === INI BAGIAN PALING PENTING YANG HILANG ===
// Impor semua fungsionalitas JavaScript dari Bootstrap (termasuk dropdown, modal, dll.)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap; // Opsional, tapi praktik yang baik

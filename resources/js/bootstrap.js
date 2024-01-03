import _ from 'lodash';
window._ = _;

import * as bootstrap from 'bootstrap'

try {
  window.bootstrap = bootstrap;
} catch (e) {}

export { bootstrap }

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
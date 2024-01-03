import './bootstrap';

import jQuery from 'jquery';
window.$ = jQuery;

import 'jquery-mask-plugin';

import Chart from 'chart.js/auto';
import { Colors } from 'chart.js';
Chart.register(Colors);

window.Chart = Chart;
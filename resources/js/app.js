import './bootstrap';

import Alpine from 'alpinejs';

import DataGrid from "./vendor/laravel-datagrid/gridjs/Components/DataGrid.vue";

app.component('data-grid', DataGrid);

window.Alpine = Alpine;

Alpine.start();

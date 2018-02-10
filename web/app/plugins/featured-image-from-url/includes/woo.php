<?php

function fifu_woo_zoom() {
    return get_option('fifu_wc_zoom') == 'toggleon' ? 'inline' : 'none';
}

function fifu_woo_lbox() {
    return get_option('fifu_wc_lbox') == 'toggleon';
}

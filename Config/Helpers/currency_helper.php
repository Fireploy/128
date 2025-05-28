<?php
function formatearMoneda($numero)
{
    return '$ ' . number_format($numero, 0, '', '.');
}

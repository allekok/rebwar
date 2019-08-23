<?php
/* Constants */
const _files = [
    [
	'out'=>'../index.html',
	'in'=>'frontend/index.html',
    ],
];

const _script = true;
const _script_needle = '{SCRIPT}';
const _script_path = 'frontend/script/main.js';

const _style = true;
const _style_needle = '{STYLE}';
const _style_path = 'frontend/style/main.css';

const _service_worker = true;
const _service_worker_needle = '{SERVICE_WORKER}';
const _service_worker_path = '/service-worker.js';
const _service_worker_scope = '/';

const _font = true;
const _font_decl_needle = '{FONT_DECL}';
const _font_needle = '{FONT}';
const _font_path = 'src/frontend/style/DroidNaskh-Regular.woff2';
const _font_name = 'kurd';
const _font_decl = "@font-face
{
    font-family:'"._font_name."';
    font-display:swap;
    font-style:normal;
    font-weight:400;
    src:url('"._font_path."') format('woff2');
}";

/* Make */
$scripts = '';
$styles = '';
$sw = '';

if(_style)
{
    $styles = file_get_contents(_style_path);
    if(_font)
    {
	$font_decl = _font_decl;
	$font_name = "'"._font_name."'";
    }
    else
    {
	$font_decl = '';
	$font_name = '';
    }
    $styles = str_replace([_font_decl_needle, _font_needle],
			  [$font_decl,$font_name], $styles);
    $styles = '<style>'.$styles.'</style>';
}

if(_script)
{
    $scripts = '<script>'.file_get_contents(_script_path).'</script>';
}

if(_service_worker)
{
    $sw = "<script>
    if ('serviceWorker' in navigator)
	navigator.serviceWorker.register('"._service_worker_path.
	  "', {scope: '"._service_worker_scope."'}); </script>";
}

// Replace 
foreach(_files as $f)
{
    $text = file_get_contents($f['in']);
    
    $text = str_replace([_script_needle, _style_needle, _service_worker_needle],
			[$scripts, $styles, $sw], $text);
    
    file_put_contents($f['out'], $text);
    
    echo "`{$f['out']}` Done.\n";
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo esc_html( $form['title'] ); ?></title>
<style type="text/css">
body {
	padding: 0;
	font-family: sans-serif;
	font-size: 13px;
}
</style>
<?php wp_head(); ?>
</head>
<body>
<?php GFFormDisplay::print_form_scripts( $form_id, false ); // ajax = false ?>
<?php gravity_form( $form_id, $display_title, $display_description ); ?>
<?php wp_footer(); ?>
</body>
</html>

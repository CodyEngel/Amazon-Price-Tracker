<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-04-27 19:51:01
         compiled from ".\templates\amazon_search_item.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16139553c25791aadd3-62538723%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad70ddbc50cb0e69e2101e4ee6a9710ace9ff650' => 
    array (
      0 => '.\\templates\\amazon_search_item.tpl',
      1 => 1430185800,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16139553c25791aadd3-62538723',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_553c2579224bd9_24004185',
  'variables' => 
  array (
    'search_item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_553c2579224bd9_24004185')) {function content_553c2579224bd9_24004185($_smarty_tpl) {?><a href="/product.php?id=<?php echo $_smarty_tpl->tpl_vars['search_item']->value->ASIN;?>
">
	<div class="amazon_search_item_container">
		<div class="amazon_image">
			<img src="<?php echo $_smarty_tpl->tpl_vars['search_item']->value->ImageURL;?>
" />
			<h4 class="amazon_price"><?php echo $_smarty_tpl->tpl_vars['search_item']->value->Price;?>
</h4>
		</div>
		<h2 class="amazon_title"><?php echo $_smarty_tpl->tpl_vars['search_item']->value->Title;?>
</h2>
	</div>
</a><?php }} ?>

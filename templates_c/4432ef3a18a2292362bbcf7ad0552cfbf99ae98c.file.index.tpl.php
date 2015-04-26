<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-04-25 18:34:21
         compiled from ".\templates\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:24402553c256fe0d1d8-48839751%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4432ef3a18a2292362bbcf7ad0552cfbf99ae98c' => 
    array (
      0 => '.\\templates\\index.tpl',
      1 => 1430008453,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24402553c256fe0d1d8-48839751',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_553c256fe4a0d3_92464959',
  'variables' => 
  array (
    'title' => 0,
    'search_keyword' => 0,
    'amazon_search' => 0,
    'search_item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_553c256fe4a0d3_92464959')) {function content_553c256fe4a0d3_92464959($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<div id="page_wrapper">
    <div id="content_wrapper">
        <form id="form_search">
            <input id="input_search" name="keyword" placeholder="Search by product or ASIN" value="<?php echo $_smarty_tpl->tpl_vars['search_keyword']->value;?>
" />
            <input id="button_search" class="button" type="submit" value="Search" />
        </form>
        
        <?php  $_smarty_tpl->tpl_vars['search_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['search_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['amazon_search']->value->SearchResultItems; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['search_item']->key => $_smarty_tpl->tpl_vars['search_item']->value) {
$_smarty_tpl->tpl_vars['search_item']->_loop = true;
?>
        	<?php echo $_smarty_tpl->getSubTemplate ("amazon_search_item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('search_item'=>$_smarty_tpl->tpl_vars['search_item']->value), 0);?>

        <?php } ?>
    </div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>

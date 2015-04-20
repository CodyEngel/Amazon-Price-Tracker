<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-04-19 19:24:10
         compiled from ".\templates\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:27255553454a9e94666-39601025%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4432ef3a18a2292362bbcf7ad0552cfbf99ae98c' => 
    array (
      0 => '.\\templates\\index.tpl',
      1 => 1429493047,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27255553454a9e94666-39601025',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_553454aa045e60_45001257',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_553454aa045e60_45001257')) {function content_553454aa045e60_45001257($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("test.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("setup", 'local'); ?>
<?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>'foo'), 0);?>


<div id="page_wrapper">
    <div id="content_wrapper">
        <h2>Amazon Store!</h2>
    </div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>

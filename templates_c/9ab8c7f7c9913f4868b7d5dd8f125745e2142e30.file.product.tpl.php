<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-04-28 19:15:01
         compiled from ".\templates\product.tpl" */ ?>
<?php /*%%SmartyHeaderCode:23149553ee394423914-04114616%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ab8c7f7c9913f4868b7d5dd8f125745e2142e30' => 
    array (
      0 => '.\\templates\\product.tpl',
      1 => 1430270098,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23149553ee394423914-04114616',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_553ee394460818_02566893',
  'variables' => 
  array (
    'amazon_product' => 0,
    'amazon_product_price_history' => 0,
    'amazon_price_item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_553ee394460818_02566893')) {function content_553ee394460818_02566893($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>$_smarty_tpl->tpl_vars['amazon_product']->value->Title), 0);?>


<div id="page_wrapper">
    <div id="content_wrapper">
        <div id="amazon_product_details">
            <h2><?php echo $_smarty_tpl->tpl_vars['amazon_product']->value->Title;?>
</h2>
            <img src="<?php echo $_smarty_tpl->tpl_vars['amazon_product']->value->ImageURL;?>
" />
            <p class="amazon_product_description">
                <?php echo $_smarty_tpl->tpl_vars['amazon_product']->value->Description;?>

            </p>
        </div>
        
        <div id="amazon_product_price_history">
            <div class="button">Add To Wishlist</div>
            <h4>Price History</h4>
            <ul>
            <?php  $_smarty_tpl->tpl_vars['amazon_price_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['amazon_price_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['amazon_product_price_history']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['amazon_price_item']->key => $_smarty_tpl->tpl_vars['amazon_price_item']->value) {
$_smarty_tpl->tpl_vars['amazon_price_item']->_loop = true;
?>
            	<li><?php echo $_smarty_tpl->tpl_vars['amazon_price_item']->value->Timestamp;?>
 - <?php echo $_smarty_tpl->tpl_vars['amazon_price_item']->value->Price;?>
</li>
            <?php } ?>
            </ul>
        </div>
    </div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>

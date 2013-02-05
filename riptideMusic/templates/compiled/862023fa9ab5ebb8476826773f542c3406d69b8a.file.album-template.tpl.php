<?php /* Smarty version Smarty-3.1.13, created on 2013-02-05 17:58:11
         compiled from "album-template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:561320907510d8fb42b1e13-02754603%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '862023fa9ab5ebb8476826773f542c3406d69b8a' => 
    array (
      0 => 'album-template.tpl',
      1 => 1360105090,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '561320907510d8fb42b1e13-02754603',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_510d8fb4315f13_82355608',
  'variables' => 
  array (
    'imageSrc' => 0,
    'albumName' => 0,
    'artistName' => 0,
    'year' => 0,
    'genre' => 0,
    'avgRating' => 0,
    'tags' => 0,
    'tag' => 0,
    'tracks' => 0,
    'track' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_510d8fb4315f13_82355608')) {function content_510d8fb4315f13_82355608($_smarty_tpl) {?><!-- album display -->
<div class="full-album-listing">
    <img width="150px" height="150px" src="<?php echo $_smarty_tpl->tpl_vars['imageSrc']->value;?>
"/>
    <h1><?php echo $_smarty_tpl->tpl_vars['albumName']->value;?>
</h1>
    <a href="/~celaya/riptideMusic/artist?name=<?php echo rawurlencode($_smarty_tpl->tpl_vars['artistName']->value);?>
">
        <p class="artistName"><?php echo $_smarty_tpl->tpl_vars['artistName']->value;?>
</p>
    </a>
    <p><?php echo $_smarty_tpl->tpl_vars['year']->value;?>
</p>
    <a href="#/<?php echo $_smarty_tpl->tpl_vars['genre']->value;?>
">
        <p><?php echo $_smarty_tpl->tpl_vars['genre']->value;?>
</p>
    </a>
    <p><?php echo $_smarty_tpl->tpl_vars['avgRating']->value;?>
</p>
    <p class="tags">
        <?php  $_smarty_tpl->tpl_vars['tag'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tag']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tags']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->key => $_smarty_tpl->tpl_vars['tag']->value){
$_smarty_tpl->tpl_vars['tag']->_loop = true;
?>
            <a href="#tag/<?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
">
                <span><?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</span>
            </a>
        <?php } ?>
    </p>
    <table>
        <?php  $_smarty_tpl->tpl_vars['track'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['track']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tracks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['track']->key => $_smarty_tpl->tpl_vars['track']->value){
$_smarty_tpl->tpl_vars['track']->_loop = true;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['track']->value['name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['track']->value['duration'];?>
</td>
            </tr>
        <?php } ?>
    </table>

    <hr>
</div>
<?php }} ?>
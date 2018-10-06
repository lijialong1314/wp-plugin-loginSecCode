<?php
/*
 * Plugin Name: 登录安全码
 * Version: 1.0
 * Description: 登录时使用微信订阅号(@PocketRobot)获取随机安全码
 * Plugin URI: https://www.coderecord.cn/login-use-telegram-code.html
 * Author: coderecord.cn
 * Author URI: https://www.coderecord.cn
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: login-sec-code
*/
$option = get_option("login_Sec_Code");
if($option['enabled'] == "1"){
	add_action('login_form','add_login_fields',1);
	add_action('login_form_login','login_val',1);
}

add_action('admin_init', 'login_sec_code_admin_init', 1);
function login_sec_code_admin_init() {
	$option = get_option("login_Sec_Code");
	$GLOBALS["weixin_id"] = $option['wxid'];
	$GLOBALS["loginSecCode_enable"] = $option["enabled"];
	
	register_setting('login_sec_code_admin_options_group', 'login_Sec_Code');
}

add_action('admin_menu', 'login_sec_code_admin_menu');
function login_sec_code_admin_menu() {
    add_options_page("登录安全码", "登录安全码", 'manage_options', 'login-sec-code', 'login_sec_code_options_page');
}

function get_seccode($wxid){
    $config = include __DIR__ ."/config.php";
    $wxid = "WPLogin_$wxid";
    $redis = new Redis();
    $redis->connect($config["host"], $config["port"]);
    $redis->auth($config["passwd"]);
    $redis->select($config["db"]);
    $value = $redis->get($wxid);
    
   if($value){
        return $value;
    }else{
        return "No Value";
    }
}

function add_login_fields() {
		echo "<p><label for='math' class='small'>验证码</label><br />使用微信订阅号(@PocketRobot)获取随机安全码<input type='password' name='seccode' class='input' value='' size='25' tabindex='4'></p>";
}

function login_val() {
	if(!isset($_POST['seccode']))
		return;
	
	$option = get_option("login_Sec_Code");
	if($option['enabled'] != 1)
		return;
	
	$seccode=$_POST['seccode'];
	$seccode2 = get_seccode($option['wxid']);
	switch($seccode){
		case  $seccode2:
			break;
		case null:wp_die('错误: 请输入安全码.');break;
		default:wp_die('错误: 安全码错误,请重试.');
	}
}


function login_sec_code_options_page(){
	?>
	    <div class="wrap">
        <h2>Login Sec Code</h2>
        <form action="options.php" method="post">
        <?php settings_fields('login_sec_code_admin_options_group'); ?>
        <table class="form-table">
			<tr valign="top">
				<th scope="row">启用</th>
				<td>
					<label><input name="login_Sec_Code[enabled]" type="checkbox" value="1"  <?php checked($GLOBALS["loginSecCode_enable"],1);?>/>
					登录时启用微信安全码</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">微信用户OPENID</th>
				<td>
					<input name="login_Sec_Code[wxid]" size="60" placeholder="微信OPENID" value="<?php echo $GLOBALS["weixin_id"] ;?>" required />
					<p>微信OPENID获取方式：在微信中搜索<small><code>PocketRobot</small></code>并关注，然后发送<small><code>/id</small></code>即可获取哦。</p>
				</td>
			</tr>
		
      </table>
        <?php submit_button();?>
		</form>
    </div>
	
<?php
}
?>
# block-class-package

## 概要
Gutenbergのブロック登録に関連して、必要なPHPの関数をcomposerで読み込むためのファイルをパッケージにまとめました。
## インストール
コマンドプロンプト等から次のように入力してください。
```
composer require itmar/block-class-package
```
## 収納されているネームスペース・クラス
namespace Itmar\BlockClassPakage;  
class ItmarEntryClass

## 変更履歴
= 1.1.1 =  
enqueueCommonStylesメソッドに渡すファイルパスをプラグインのルートフォルダより上位のフォルダ内のファイルパスを渡すことができるように修正

= 1.1.0 =  
enqueueCommonStylesメソッドを追加

= 1.0.4 =
- 登録するブロックが単数の場合でも対応できるよう機能を追加
- 依存するプラグインがインストールされていない又は有効化されていない場合のエラーメッセージを修正

= 1.0.3 =  
wp_set_script_translationsのハンドルを"ブロック名-editor-script"とすることでblock.jsonにデフォルトで設定されている"editorScript": "file:./index.js",を維持しながら、wp_set_script_translationsの機能を維持するようにした。

= 1.0.2 =  
WordPress6.5対応のためwp_register_scriptではなくwp_enqueue_scriptでハンドルを使用するようにしました。

= 1.0.1 =  
引数名のスペルミスを修正しました。

= 1.0.0 =  
最初のリリース

## メソッドの機能と引数
### block_init(string $text_domain, string $file_path)
#### 説明
$file_path内に含まれている複数のブロックを登録します。同時にPHPとJavascriptの翻訳関数をセットします。翻訳のためのpot,po,moの各ファイルはプラグインのルートフォルダ内のlanguagesフォルダに配置されていることが必要です。
また、WordPressの関数で取得する変数をフロントエンドのJavaScriptで使用できるようにローカライズします。
ローカライズされた変数はテキストドメイン名の'-'を'_'に置換した名称のオブジェクトに次のように収納されます。  
home_url・・・WordPressサイトのホームURL  
plugin_url・・・プラグインルートのURL  
#### 引数
- `$text_domain` string 必須  
プラグインで設定したテキストドメイン名
- `$file_path` string 必須  
プラグインのルートフォルダへの絶対パス。通常は__FILE__を設定する。
#### 戻り値
なし
#### 呼び出し例
```
$block_entry = new \Itmar\BlockClassPakage\ItmarEntryClass();

add_action('init', function () use ($block_entry) {
	$block_entry->block_init('text-domain', __FILE__);
});
```

### activation_check(array $plugin_data, array $plugin_slug)
#### 説明
$plugin_slugで指定された名称のプラグインがインストール又は有効化されていない場合、そのプラグイン名等（最初に発見された１つだけ）を表示して、処理を中止します。
#### 引数
- `$plugin_data` array 必須  
get_plugin_dataで取得したプラグインの情報
- `$plugin_slug` array 必須  
依存する（チェックする）プラグインの名称（string）。配列で指定してください。
#### 戻り値
なし
#### 呼び出し例
```
$block_entry = new \Itmar\BlockClassPakage\ItmarEntryClass();

register_activation_hook(__FILE__, function () use ($block_entry) {
	$plugin_data = get_plugin_data(__FILE__);
	$block_entry->activation_check($plugin_data, ['block-collections']); // ここでメソッドを呼び出し
});
```

### show_admin_dependency_notices(array $plugin_data, array $plugin_slug)
#### 説明
$plugin_slugで指定された名称のプラグインがインストール又は有効化されていない場合、そのプラグイン名等（最初に発見された１つだけ）を管理画面に表示させます。
#### 引数
- `$plugin_data` array 必須  
get_plugin_dataで取得したプラグインの情報
- `$plugin_slug` array 必須  
依存する（チェックする）プラグインの名称（string）。配列で指定してください。
#### 戻り値
なし
#### 呼び出し例
```
$block_entry = new \Itmar\BlockClassPakage\ItmarEntryClass();

add_action('admin_notices', function () use ($block_entry) {
	$plugin_data = get_plugin_data(__FILE__);
	$block_entry->show_admin_dependency_notices($plugin_data, ['block-collections']);
});
```

### enqueueCommonStyles(string $filePath, boolean $isEditor, string $packageName)
#### 説明
インストールされているnpmパッケージ内のindex.css、style-index.cssをエンキューします。依存元のプラグインで、この関数を実行することで、依存しているプラグインでも共通のCSSを充てることができます。
#### 引数
- `$filePath` string 必須  
プラグインのエントリポイントファイルがあるフォルダへの絶対パス。原則として`--FILE--`を指定する。
- `$isEditor` boolean 必須  
ブロックエディタに適用させるときはtrue、フロントエンドに適用させるときはfalse。
原則としてenqueue_block_assetsフックで呼び出すときはtrue、wp_enqueue_scriptsフックで呼び出すときはfalse.
- `$packageName` boolean 必須  
index.css、style-index.cssが格納されているnpmパッケージの名前
#### 戻り値
なし
#### 呼び出し例
```
$block_entry = new \Itmar\BlockClassPakage\ItmarEntryClass();

add_action('enqueue_block_assets', function () use ($block_entry) {
	$block_entry->enqueueCommonStyles(__FILE__, true, "itmar-block-packages");
});
add_action('wp_enqueue_scripts', function () use ($block_entry) {
	$block_entry->enqueueCommonStyles(__FILE__, false, "itmar-block-packages");
});

```






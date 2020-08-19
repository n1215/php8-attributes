# PHP8のAttributesの使い途模索

### 初期化

```
docker-compose run composer composer install
```

## Docker環境
PHPビルトインサーバ + Composer実行用

### 起動

```
docker-compose up
```

### 停止

```
docker-compose down
```

## テスト内容

### Requestからのパラメータ抽出
ビルトインサーバ
```
curl http://localhost:8000/hello?name=Taro
```

### アノテーションによるRouting
未実装

### テキスト付きEnum

テストスクリプト実行
```
docker-compose exec php php ./console/enum.php
```

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

### Webフレームワーク用

```
curl http://localhost:8000/hello?name=Taro
```

- HTTPリクエストからのパラメータ抽出 (src/RequestExtractor)
- HTTPレスポンスへの変換（src/Responder）
- アノテーションによるルーティング (src/Routing) : 未実装

### コンソール

```
docker-compose exec php php ./console/enum.php
```

- テキスト付きEnum (src/Enum)
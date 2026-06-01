# 勤怠管理アプリ

## 環境構築

Dockerビルド

1. リポジトリをクローン
```bash
git clone リポジトリURL
```

2. コンテナを起動
```bash
./vendor/bin/sail up -d --build
```

Laravel環境構築

1. composerパッケージのインストール
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

2. .envファイルの作成
```bash
cp .env.example .env
```

3. アプリケーションキーの生成
```bash
./vendor/bin/sail artisan key:generate
```

4. マイグレーションの実行
```bash
./vendor/bin/sail artisan migrate
```

5. シーディングの実行
```bash
./vendor/bin/sail artisan db:seed
```

## 使用技術（実行環境）

- PHP 8.5
- Laravel 13.x
- MySQL 8.0
- Docker / Laravel Sail

## ER図

＜ER図の画像をここに貼り付けてください＞

## URL

- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/
- Mailhog：http://localhost:8025/
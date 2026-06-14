# アプリケーション名
coachtech勤怠管理アプリ<br>
Laravel を用いて作成した勤怠管理アプリです。<br>
ユーザー登録・ログイン機能、勤怠登録、休憩登録、管理者画面など勤怠管理に必要な基本機能を実装しています。<br>

## 実装機能
・ユーザー登録<br>
・ログイン認証<br>
・プロフィール情報の管理<br>
・バリデーション<br>
・DB保存<br>
・ダミーデータ生成（Seeder）<br>

## 環境構築
Dockerビルド<br>
・git clone git@github.com:haizi0511/test-contactform.git<br>
・docker-compose up -d --build<br>

### Laravel環境構築
・docker-compose exec php bash<br>
・composer install<br>
・cp .env.example .env、環境変数を適宜変更<br>
・php artisan key:generate<br>
・php artisan migrate<br>
・php artisan db:seed<br>

### ダミーデータ（Seeder）について
本アプリには 動作確認用のダミーデータ生成機能 を実装しています。<br>
生成されるデータ<br>
・管理者ユーザー 1 名<br>
・一般ユーザー 10 名<br>
・各ユーザーの勤怠データ<br>
・休憩データ<br>
・勤怠と休憩を結ぶ中間テーブルのデータ<br>

実行コマンド<br>
php artisan migrate:fresh --seed<br>

ログイン情報について<br>
ダミーデータのユーザーのパスワードは全て「password」です。<br>

### 開発環境
・一般ユーザーログイン：http://localhost/login<br>
・ユーザー登録：http://localhost/register<br>
・管理者ユーザーログイン：http://localhost/admin/login<br>
・phpMyAdmin：http://localhost:8080/<br>

## 使用技術(実行環境)
・PHP:8.1.34<br>
・Laravel 8.83.29<br>
・MySQL 8.0.26<br>
・nginx 1.21.1<br>
・JavaScript<br>

## ER図
<img width="682" height="584" alt="ER図" src="https://github.com/user-attachments/assets/e249f049-0fc8-43d5-ad2e-223f5ac9574e" />

## URL
・一般ユーザーログイン：http://localhost/login<br>
・ユーザー登録：http://localhost/register<br>
・管理者ユーザーログイン：http://localhost/admin/login<br>


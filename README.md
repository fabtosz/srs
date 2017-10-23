Symfony Standard Edition
========================

System Rezerwacji Sal - projekt grupowy

Instrukcja dla kontrybutorów:

1) Włącz pakiet XAMPP i serwisy PHP i MySQL
2) W folderze projektu wpisz komendę:
	composer install
3) Następnie utwórz bazę danych wywołując
	php bin/console doctrine:database:create
4) Utwórz tabelę w powstałej przed chwilą bazie i je zaktualizuj komendą:
	php bin/console doctrine:schema:update --force
4) Dodaj kilka rekordów SAL (tabela 'classroom') - przez odpowiednie zapytanie SQL, phpmyadmin - jak wygodniej.
   Komenda do wpisywania zapytań:
	php bin/console doctrine:query:sql "treść_zapytania"
5) Uruchomienie lokalnego serwera:
	php bin/console server:run

Chyba wszystko. Jak coś nie będzie działać albo o czymś zapomniałem to priv :)

[Aktualizacja 23-10-2017r.]

Po zmianach w encjach projektu (łącznie z utworzeniem nowych lub usuwaniem) należy użyć komendy
	php bin/console doctrine:schema:update --force
Dodatkowo sugeruję usunięcie wszystkich starych rekordów z aktualizowanej dzięki encji tabeli wszystkich nieaktualnych rekordów odpowiednim zapytaniem SQL.

Po doinstalowaniu dodatkowego bundla w projekcie, po użyciu git pull należy wpisać komendę
	composer update
Zaś po sklonowaniu
	composer install

Aby dodać użytkownika do bazy danych należy użyć komendy
	php bin/console fos:user:create login mail haslo
Aby przypisać użytkownikowi rolę admina należy na istniejącym użytkowniku użyć komendy
	php app/console fos:user:promote wybrany_login_uzytkownika ROLE_ADMIN


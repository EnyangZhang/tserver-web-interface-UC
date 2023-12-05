# tserver Web Interface

## Team members
    (75400547) Hezekiah, Dacillo
    (72004822) Enyang, Zhang
    (25082165) Bach, Vu Viet
    (68697438) Linh, Luu Khanh

## App setup

### Login Credentials (testing only):

- Exam Officer: `ezh15` (`1234`)
- Course coordinator: `bvv10` (`1234`)
- Tutor: `kll60` (`1234`)

### Where to find stuff (folder)

- `lib`: Connection, Data structure, static methods
- `css`: Local Stylesheet
- `js`:	Local Js, animation
- `pages`: php UI
- `phpObj`: html object to be included to php pages
- `img`: image resources
- `doc`: Documentation, DB backup
- Database connection: `lib/config.php`
- Unactive Timeout: `dbconnect.php/CheckInactivity()` Default 10 mins

## Git quick hand

|Git command|Use when|
|---|---|
| `git push` | Push your Feature branch |
| `git pull` | Pull your Feature branch |
| `git merge` | Merge dev to feature branch |
| `pull request` | Merge feature to dev |

- Don't touch master branch until all member agree
- Read/write to dev only

## Database

	`doc/tserver.sql`: Given template
	`doc/info263_tserver.sql`: Export with changes DB (for backup/local use)
	`doc/procedure.sql`: Backup procedure
	
	When import sql Dump from server to Local:
	Replace `hdd19`@`%` to `root`@`localhost`
	Replace `utf8mb4_ai_ci` `utf8mb4_general_ci`

## Useful Link

- Submission: https://learn.canterbury.ac.nz/mod/assign/view.php?id=1365109

## Reference of resources:

- Login template: https://colorlib.com/wp/template/login-form-v3/

# vlmis-all-modules

For database configuration. Change below lines in application/configs/application.ini

doctrine.db.driver = "pdo_mysql"

doctrine.db.host = "localhost"

doctrine.db.user = "root"

doctrine.db.password = ""

doctrine.db.dbname = "vlmis"

*******************************************************

Database configuration for readonly server:

doctrine_read.db.driver = "pdo_mysql"

doctrine_read.db.host = "localhost"

doctrine_read.db.user = "root"

doctrine_read.db.password = ""

doctrine_read.db.dbname = "vlmis"

*******************************************************

For SMTP configuration change these lines

smtpConfig.host = ""

smtpConfig.ssl = "ssl"

smtpConfig.port = "465"

smtpConfig.auth = ""

smtpConfig.username = ""

smtpConfig.password = ""

smtpConfig.fromAddress = ""

smtpConfig.fromName = ""

smtpConfig.toAddress = ""

smtpConfig.toName = ""

smtpConfig.isSendMails = true

*******************************************************

For enable application errors use

phpSettings.display_startup_errors = 1

phpSettings.display_errors = 1

resources.frontController.params.displayExceptions = 1


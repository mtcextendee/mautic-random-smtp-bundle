# Random SMTP for Mautic

Random SMTP server support for Mautic

### Installation from command line

1. `composer require mtcextendee/mautic-random-smtp-bundle`
2. `php app/console mautic:plugins:reload`

### Manual installation

1. Download last version https://github.com/mtcextendee/mautic-random-smtp-bundle/releases
2. Unzip files to plugins/MauticRandomSmtpBundle
3. Go to /s/plugins/reload
4. See RandomSMTP plugin

### Setup plugin

#### Plugin configuration

1. Paste SMTP servers to plugins setting (comma separated list of SMTP servers on each line)

You can use columns:

  - host
  - username
  - password
  - port - Is not required. Default is 25
  - authenitication mode - is not required. Default is none. Other options are plain, login or cram-md5
  - encryption - is not required. Default is none. Other options are ssl or tls

2. Match column number with each parameter. Start from 0

![image](https://user-images.githubusercontent.com/462477/55195617-652ab300-51ad-11e9-9565-b2bb03e49543.png)

3. Go to configurations and choose Random SMTP from service provider list

![image](https://user-images.githubusercontent.com/462477/55195914-34974900-51ae-11e9-888b-0ceabb60ebf1.png)

### More

Extensions family for Mautic <a href="https://mtcextendee.com" target="_blank">mtcextendee.com</a>

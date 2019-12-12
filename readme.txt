RU: --------------------------------------------------------------------------
это форк плагина с https://github.com/bhadaway/stop-spammers

Первое:
идеология этой ветки (версии) - профилактика.
спамер, хакер и прочие - должны быть заблокированы ДО регистрации и до ВХОДА в блог. 
соответственно - мз плагина будет удалено все, что связано с ловлей  спама на форуме/блоге.
Превентивные меры - главная цель.
Второе:
метод определения плохого пользователя - DNSBL и веь сервисы stopforumspam, blocklistde, abuseipdb  
 - будет возможность слать рапорт об попытке регистрации или логин. Также, будет возможность блокировать
(весь или только к регистрации) доступ в зависимости от страны ip и/или ASN 
 - надо регулярно обновлять базы ipБ сейчас только от maxmind, но будет возможность использования других сервисов.

EN: --------------------------------------------------------------------------
this is a fork plugin with https://github.com/bhadaway/stop-spammers

First:
the ideology of this branch (version) is prevention.
spammer, hacker and others - must be blocked BEFORE registration and before ENTERING the blog.
accordingly - the plug-in will remove everything related to catching spam on the forum / blog.
Preventive measures are the main goal.
Second:
The method for determining a bad user - DNSBL and the services stopforumspam, blocklistde, abuseipdb
 - will be able to send a report about an attempt to register or login.
Also, it will be possible to block (all or only to registration) access depending on the country ip 
and/or ASN - you need to regularly update ip databases - now only from maxmind,
 but there will be an opportunity to use other services.
<?php
/*
	autogen by [729]jetsam
	from "IP2LOCATION-LITE-DB1.CSV" (fdate:2019/12/01 15:00:00)
	[NP] Nepal
*/

class chkNP extends be_module {

	public $searchname = 'Nepal';

	public $searchlist = [
		['027034000000','027034127255'],
		['027111016000','027111031255'],
		['036000004000','036000007255'],
		['036252000000','036253255255'],
		['043228192000','043228195255'],
		['043231208000','043231211255'],
		['043243096000','043243099255'],
		['043245084000','043245087255'],
		['043245092000','043245095255'],
		['043245236000','043245239255'],
		['045064160000','045064163255'],
		['045112180000','045112183255'],
		['045115216000','045115219255'],
		['045116020000','045116023255'],
		['045117152000','045117155255'],
		['045121032000','045121035255'],
		['045123220000','045123223255'],
		['049126000000','049126255255'],
		['049236212000','049236215255'],
		['049244000000','049244255255'],
		['057092224000','057092239255'],
		['101251004000','101251007255'],
		['103001092000','103001095255'],
		['103005150000','103005150255'],
		['103005228000','103005229255'],
		['103010028000','103010031255'],
		['103028084000','103028087255'],
		['103038196000','103038199255'],
		['103043168000','103043171255'],
		['103048088000','103048088255'],
		['103051016000','103051019255'],
		['103052024000','103052031255'],
		['103057204000','103057207255'],
		['103058144000','103058145255'],
		['103065200000','103065201255'],
		['103069124000','103069127255'],
		['103071242000','103071243255'],
		['103074014000','103074015255'],
		['103075048000','103075049255'],
		['103075148000','103075149255'],
		['103081136000','103081136255'],
		['103083228000','103083229255'],
		['103086056000','103086057255'],
		['103089156000','103089159255'],
		['103090084000','103090087255'],
		['103090144000','103090147255'],
		['103093122000','103093123255'],
		['103094158000','103094159255'],
		['103094220000','103094223255'],
		['103094252000','103094255255'],
		['103095016000','103095019255'],
		['103096032000','103096035255'],
		['103096244000','103096247255'],
		['103098128000','103098131255'],
		['103101036000','103101039255'],
		['103101236000','103101237255'],
		['103101252000','103101252255'],
		['103102036000','103102037255'],
		['103104028000','103104031255'],
		['103104197000','103104197255'],
		['103104232000','103104235255'],
		['103104248000','103104251255'],
		['103106146000','103106147255'],
		['103109228000','103109231255'],
		['103114024000','103114027255'],
		['103115084000','103115087255'],
		['103115167000','103115167255'],
		['103116048000','103116048255'],
		['103117092000','103117095255'],
		['103121172000','103121173255'],
		['103124096000','103124099255'],
		['103125025000','103125027255'],
		['103126244000','103126247255'],
		['103127048000','103127049255'],
		['103127062000','103127063255'],
		['103129132000','103129135255'],
		['103130207000','103130207255'],
		['103132004000','103132007255'],
		['103134072000','103134072255'],
		['103134216000','103134219255'],
		['103137010000','103137010255'],
		['103137200000','103137203255'],
		['103138160000','103138161255'],
		['103139152000','103139152255'],
		['103139254000','103140001255'],
		['103140053000','103140053255'],
		['103140132000','103140133255'],
		['103141029000','103141029255'],
		['103142196000','103142197255'],
		['103143211000','103143211255'],
		['103144106000','103144107255'],
		['103144195000','103144195255'],
		['103192076000','103192079255'],
		['103198008000','103198009255'],
		['103204220000','103204223255'],
		['103207080000','103207083255'],
		['103210013000','103210013255'],
		['103211148000','103211151255'],
		['103212064000','103212065255'],
		['103213031000','103213031255'],
		['103213124000','103213127255'],
		['103214076000','103214079255'],
		['103225244000','103225247255'],
		['103232152000','103232155255'],
		['103232228000','103232231255'],
		['103233056000','103233059255'],
		['103233182000','103233182255'],
		['103235196000','103235199255'],
		['103250132000','103250135255'],
		['103254180000','103254187255'],
		['103255126000','103255126255'],
		['110034000000','110034031255'],
		['110044112000','110044127255'],
		['111119032000','111119063255'],
		['113199128000','113199255255'],
		['116066192000','116066199255'],
		['116068208000','116068215255'],
		['116090224000','116090239255'],
		['116204172000','116204175255'],
		['117121224000','117121239255'],
		['118091160000','118091175255'],
		['120089096000','120089127255'],
		['123253120000','123253123255'],
		['124041192000','124041255255'],
		['137059008000','137059011255'],
		['139005068000','139005075255'],
		['150107204000','150107207255'],
		['154083004000','154083004255'],
		['156236030000','156236031255'],
		['157167089000','157167089255'],
		['163047148000','163047151255'],
		['163053024000','163053027255'],
		['172069076000','172069079255'],
		['182050064000','182050067255'],
		['182054156000','182054159255'],
		['182093064000','182093095255'],
		['183091132000','183091135255'],
		['202008108000','202008111255'],
		['202038004000','202038007255'],
		['202045144000','202045147255'],
		['202050052000','202050055255'],
		['202051000000','202051003255'],
		['202051064000','202051095255'],
		['202052000000','202052001255'],
		['202052224000','202053003255'],
		['202063240000','202063247255'],
		['202070064000','202070095255'],
		['202079032000','202079063255'],
		['202094066000','202094066255'],
		['202129248000','202129251255'],
		['202161135000','202161135255'],
		['202161159000','202161159255'],
		['202166192000','202166223255'],
		['203078160000','203078175255'],
		['203088072000','203088072255'],
		['203119088000','203119091255'],
	];
}

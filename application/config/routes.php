<?php

return [
	// MainController
	'' => [
		'controller' => 'main',
		'action' => 'index',],
	'sgtf544546525424' => [
		'controller' => 'main',
		'action' => 'staticmoney',],
    'phone' => [
        'controller' => 'main',
        'action' => 'phone',],
	// AdminController
	'admin/login' => [
		'controller' => 'admin',
		'action' => 'login',
	],
	'admin/logout' => [
		'controller' => 'admin',
		'action' => 'logout',
	],
	'admin/add/{idmaster:\d+}' => [
		'controller' => 'admin',
		'action' => 'add',
	],
	'admin/edit/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'edit',
	],
	'admin/delete/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'delete',
	],
	'admin/posts' => [
		'controller' => 'admin',
		'action' => 'posts',
	],
	'admin/posts/{idmaster:\d+}/{page:\d+}' => [
		'controller' => 'admin',
		'action' => 'posts',
	],
	'admin/posts/{idmaster:\d+}' => [
		'controller' => 'admin',
		'action' => 'posts',
	],
	'admin/sdacha/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'sdacha',
	],
	'admin/inwork/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'inwork',
	],
	'admin/gotov/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'gotov',
	],
	'admin/zarplata' => [
		'controller' => 'admin',
		'action' => 'zarplata',
	],
	'admin/sot' => [
		'controller' => 'admin',
		'action' => 'sot',
	],
	'admin/sot/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'sotid',
	],
	'admin/sotdel/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'sotdel',
	],
	'admin/prof' => [
		'controller' => 'admin',
		'action' => 'prof',
	],
	'admin/search' => [
		'controller' => 'admin',
		'action' => 'search',
	],
	'admin/otpusk/{id:\d+}' => [
		'controller' => 'admin',
		'action' => 'otpusk',
	],
    'admin/works/{idmaster:\d+}' => [
        'controller' => 'admin',
        'action' => 'works',
    ],
    'admin/works/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'admin',
        'action' => 'works',
    ],
    'admin/nooplata/{idmaster:\d+}' => [
        'controller' => 'admin',
        'action' => 'nooplata',
    ],
    'admin/nooplata/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'admin',
        'action' => 'nooplata',
    ],
    'admin/static' => [
        'controller' => 'admin',
        'action' => 'static',
    ],
    'admin/delfile/{namefile:.+}' => [
        'controller' => 'admin',
        'action' => 'delfile',
    ],
    'admin/lena' => [
        'controller' => 'admin',
        'action' => 'lena',
    ],
    'admin/year' => [
		'controller' => 'admin',
		'action' => 'year',
	],
	'admin/year/{year:\d+}/yearposts' => [
		'controller' => 'admin',
		'action' => 'yearposts',
	],
	'admin/year/{year:\d+}/yearposts/{idmaster:\d+}/{page:\d+}' => [
		'controller' => 'admin',
		'action' => 'yearposts',
	],
	'admin/year/{year:\d+}/yearposts/{idmaster:\d+}' => [
		'controller' => 'admin',
		'action' => 'yearposts',
	],
	'admin/year/{year:\d+}/yearworks/{idmaster:\d+}' => [
        'controller' => 'admin',
        'action' => 'yearworks',
    ],
    'admin/year/{year:\d+}/yearworks/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'admin',
        'action' => 'yearworks',
    ],
    'admin/year/{year:\d+}/yearnooplata/{idmaster:\d+}' => [
        'controller' => 'admin',
        'action' => 'yearnooplata',
    ],
    'admin/year/{year:\d+}/yearnooplata/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'admin',
        'action' => 'yearnooplata',
    ],
    'admin/rasxod' => [
		'controller' => 'admin',
		'action' => 'rasxod',
	],
	'admin/rasxodadd' => [
		'controller' => 'admin',
		'action' => 'rasxodadd',
	],
    'admin/setting' => [
        'controller' => 'admin',
        'action' => 'setting',
    ],
    'admin/send' => [
        'controller' => 'admin',
        'action' => 'send',
    ],
	// managerController
	'manager/login' => [
		'controller' => 'manager',
		'action' => 'login',
	],
	'manager/logout' => [
		'controller' => 'manager',
		'action' => 'logout',
	],
	'manager/add/{idmaster:\d+}' => [
		'controller' => 'manager',
		'action' => 'add',
	],
	'manager/edit/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'manager',
		'action' => 'edit',
	],
	'manager/posts' => [
		'controller' => 'manager',
		'action' => 'posts',
	],
	'manager/posts/{idmaster:\d+}/{page:\d+}' => [
		'controller' => 'manager',
		'action' => 'posts',
	],
	'manager/posts/{idmaster:\d+}' => [
		'controller' => 'manager',
		'action' => 'posts',
	],
	'manager/sdacha/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'manager',
		'action' => 'sdacha',
	],
	'manager/inwork/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'manager',
		'action' => 'inwork',
	],
	'manager/gotov/{idmaster:\d+}/{id:\d+}' => [
		'controller' => 'manager',
		'action' => 'gotov',
	],
	'manager/search' => [
		'controller' => 'manager',
		'action' => 'search',
	],
    'manager/works/{idmaster:\d+}' => [
        'controller' => 'manager',
        'action' => 'works',
    ],
    'manager/works/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'manager',
        'action' => 'works',
    ],
     'manager/r/{idmaster:\d+}' => [
        'controller' => 'manager',
        'action' => 'r',
    ],
    'manager/r/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'manager',
        'action' => 'r',
    ],
    'manager/nooplata/{idmaster:\d+}' => [
        'controller' => 'manager',
        'action' => 'nooplata',
    ],
    'manager/nooplata/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'manager',
        'action' => 'nooplata',
    ],
    'manager/year' => [
		'controller' => 'manager',
		'action' => 'year',
	],
	'manager/year/{year:\d+}/yearposts' => [
		'controller' => 'manager',
		'action' => 'yearposts',
	],
	'manager/year/{year:\d+}/yearposts/{idmaster:\d+}/{page:\d+}' => [
		'controller' => 'manager',
		'action' => 'yearposts',
	],
	'manager/year/{year:\d+}/yearposts/{idmaster:\d+}' => [
		'controller' => 'manager',
		'action' => 'yearposts',
	],
	'manager/year/{year:\d+}/yearworks/{idmaster:\d+}' => [
        'controller' => 'manager',
        'action' => 'yearworks',
    ],
    'manager/year/{year:\d+}/yearworks/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'manager',
        'action' => 'yearworks',
    ],
    'manager/year/{year:\d+}/yearnooplata/{idmaster:\d+}' => [
        'controller' => 'manager',
        'action' => 'yearnooplata',
    ],
    'manager/year/{year:\d+}/yearnooplata/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'manager',
        'action' => 'yearnooplata',
    ],
     'manager/rasxod' => [
		'controller' => 'manager',
		'action' => 'rasxod',
	],
	'manager/rasxodadd' => [
		'controller' => 'manager',
		'action' => 'rasxodadd',
	],
    'manager/send' => [
        'controller' => 'manager',
        'action' => 'send',
    ],
    //master
    'master/login' => [
        'controller' => 'master',
        'action' => 'login',
    ],
    'master/logout' => [
        'controller' => 'master',
        'action' => 'logout',
    ],
    'master/posts' => [
        'controller' => 'master',
        'action' => 'posts',
    ],
    'master/posts/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'master',
        'action' => 'posts',
    ],
    'master/posts/{idmaster:\d+}' => [
        'controller' => 'master',
        'action' => 'posts',
    ],
    'master/inwork/{idmaster:\d+}/{id:\d+}' => [
        'controller' => 'master',
        'action' => 'inwork',
    ],
    'master/gotov/{idmaster:\d+}/{id:\d+}' => [
        'controller' => 'master',
        'action' => 'gotov',
    ],
    'master/search' => [
        'controller' => 'master',
        'action' => 'search',
    ],
    'master/works/{idmaster:\d+}' => [
        'controller' => 'master',
        'action' => 'works',
    ],
    'master/works/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'master',
        'action' => 'works',
    ],
    'master/year' => [
        'controller' => 'master',
        'action' => 'year',
    ],
    'master/year/{year:\d+}/yearposts' => [
        'controller' => 'master',
        'action' => 'yearposts',
    ],
    'master/year/{year:\d+}/yearposts/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'master',
        'action' => 'yearposts',
    ],
    'master/year/{year:\d+}/yearposts/{idmaster:\d+}' => [
        'controller' => 'master',
        'action' => 'yearposts',
    ],
    'master/year/{year:\d+}/yearworks/{idmaster:\d+}' => [
        'controller' => 'master',
        'action' => 'yearworks',
    ],
    'master/year/{year:\d+}/yearworks/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'master',
        'action' => 'yearworks',
    ],
    'master/year/{year:\d+}/yearnooplata/{idmaster:\d+}' => [
        'controller' => 'master',
        'action' => 'yearnooplata',
    ],
    'master/year/{year:\d+}/yearnooplata/{idmaster:\d+}/{page:\d+}' => [
        'controller' => 'master',
        'action' => 'yearnooplata',
    ],
    'master/rasxod' => [
        'controller' => 'master',
        'action' => 'rasxod',
    ],
    'master/rasxodadd' => [
        'controller' => 'master',
        'action' => 'rasxodadd',
    ],
];

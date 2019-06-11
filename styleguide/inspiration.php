<?php
$items   = array(
	'http ://incisive.nu/'                                 => 'Typography',
	'https://patrickmarsceill.com/'                        => 'Color scheme, photo',
	'https://wilsonminer.com/'                             => '',
	'https://subformapp.com/'                              => 'Header',
	'http://v6.robweychert.com/'                           => 'color scheme, grid, overall design',
	'http://randsinrepose.com/'                            => 'Minimalism, typography',
	'http://danmall.me/blogroll/'                          => 'List of personal sites to draw inspiration from',
	'https://paulstamatiou.com/about/'                     => 'Timeline of accomplishments on about page',
	'https://aaronparecki.com/life-stack/'                 => 'List of products and services he reccomends',
	'http://www.charliewaite.me/'                          => 'Layout',
	'http://tobiasahlin.com/blog/introduction-to-chartjs/' => 'Blog header, Merriwether font',
	'https://taproot.agency'                               => 'Cards',
	'http://www.thenerodesign.com/seeweb'                  => 'Yellow color, interesting layout',
	'https://themes.redradar.net/museum/'                  => 'I like the framing and spacing',
	'https://www.brandonsavage.net/'                       => 'Simplicity',
	'https://mycolor.space/?hex=%230099FF&sub=1'           => 'Classy Pallete - color scheme',
	'https://www.sushiandrobots.com/consulting'            => 'A consulting page',
	'https://www.studio-job.com'                           => 'Cartoony',
	'https://s-i-l-o.fr/'                                  => 'Brutal',
	'https://adamsilver.io/'                               => 'Minimal, simple layout that gets the job done',
	'http://www.adamriddle.com/'                           => 'Case studies, cards, minimal layout',
	'https://www.zachleat.com/web/best-of/'                => 'How to calculate the most popular via Google Analytics data. See the archives for ideas on how to display it',
);
$context = array(
	'items' => $items,
);
Sprig::out( 'styleguide-inspiration.twig', $context );

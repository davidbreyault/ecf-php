-- --------------------------------------------------------
--
-- Dumping data for table category
--
-- --------------------------------------------------------
INSERT INTO category (`id`, `name`) VALUES
(1, 'Non-classée'),
(2, 'Back-End'),
(3, 'Front-End');

-- --------------------------------------------------------
--
-- Dumping data for table company
--
-- --------------------------------------------------------
INSERT INTO company (`id`, `name`, `address`, `postcode`, `town`, `phone_number`) VALUES
(1, 'IS Corp', '30 Boulevard des Bords de Loire', '37000', 'TOURS', '0247001000'),
(2, 'Capgemini', '56 Avenue du Colonel Pierre Avia', '92130', 'ISSY-LES-MOULINEAUX', '0122395678');

-- --------------------------------------------------------
--
-- Dumping data for table technology
--
-- --------------------------------------------------------
INSERT INTO technology (`id`, `category_id`, `name`) VALUES
(1, 3, 'HTML'),
(2, 3, 'CSS'),
(3, 3, 'Javascript'),
(4, 2, 'PHP'),
(5, 3, 'React JS'),
(6, 3, 'Angular JS'),
(7, 3, 'Vue JS'),
(8, 3, 'jQuery'),
(9, 2, 'Laravel'),
(10, 2, 'Symfony'),
(11, 3, 'SASS'),
(12, 1, 'Git'),
(13, 3, 'Figma'),
(14, 2, 'My SQL'),
(15, 2, 'My SQL Work Bench'),
(16, 1, 'WordPress'),
(17, 2, 'Ruby on Rails'),
(18, 2, 'Python'),
(19, 1, 'C++'),
(20, 3, 'Bootstrap'),
(21, 3, 'Tailwind CSS');

-- --------------------------------------------------------
--
-- Dumping data for table `user`
--
-- --------------------------------------------------------
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `gender`, `birth`, `address`, `postcode`, `town`, `phone_number`, `availability`, `is_employed`, `created_at`, `updated_at`) VALUES
(1, 'paul-auchon@is-corp.fr', '[\"ROLE_ADMIN\"]', '$2y$13$F71iD067gdiK5cIArQc.qOOpe8bi1jZpH7as1jucHMK9HlsQNGkHa', 'Paul', 'AUCHON', 'M', '1985-08-23', '31 rue des Hirondelles', '37000', 'TOURS', '0600232111', '2021-08-11', 1, '2021-08-11 17:31:13', '2021-08-11 17:31:13'),
(2, 'marc-hassin@is-corp.fr', '[\"ROLE_COMMERCIAL\"]', '$2y$13$cYtHA8maidWPeEmitxGTZ.nUhwNr83OPIRyjXfhWE.YvTgZO5/RhG', 'Marc', 'HASSIN', 'M', '1986-02-26', '47 rue Jean Moulin', '37000', 'SAINT AVERTIN', '0600102030', '2021-08-11', 1, '2021-08-11 15:41:26', '2021-08-13 07:51:46'),
(3, 'annie-mahle@is-corp.fr', '[\"ROLE_EMPLOYEE\"]', '$2y$13$8zzq.TIc/86YIBUVqOn4m.cRprNxTokkcAqau/nAeh8JRFC5QZDbi', 'Annie', 'MAHLE', 'F', '1982-12-01', '9 rue des Douves', '37500', 'CHINON', '0601103111', '2021-08-11', 1, '2021-08-11 15:44:09', '2021-08-26 07:01:32'),
(4, 'laureloge@jaimel.fr', '[]', '$2y$13$h50hOtgcJTTxh7PYLq5QCO/QEiT3Gq.LpaC2R/vg6dbZzj28HXQOS', 'Laure', 'LOGE', 'F', '1994-04-10', '10 place des Vosges', '88000', 'EPINAL', '0601020304', '2021-11-02', 0, '2021-08-11 15:49:00', '2021-08-11 15:49:00'),
(5, 'john-doe@is-corp.fr', '[\"ROLE_EMPLOYEE\"]', '$argon2id$v=19$m=65536,t=4,p=1$fR0tpFDOxBfURVWTek5/AQ$1/3LTBYH6qAxiPgUCdCRfGeXbWXqdVnu/5En75EmuXQ', 'John', 'DOE', 'M', '1992-12-02', '5 rue Monsieur Le Prince', '37230', 'FONDETTES', '0556543345', '2021-08-11', 1, '2021-08-11 16:03:29', '2021-08-16 12:33:29'),
(7, 'pacomelaizautre@jaimel.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$84Pv5RtDj5bFrwMC3Ke3Zw$/urRAiK+uLLu+EgBh8MuNckrSHw/N++tR/2gZSK0NAY', 'Pacôme', 'LAIZAUTRE', 'M', '1996-03-03', '174 rue des Gaulois', '06000', 'NICE', '0655105510', '2021-10-20', 0, '2021-08-11 16:36:51', '2021-08-11 16:39:11'),
(8, 'lea-ricot@is-corp.fr', '[\"ROLE_COMMERCIAL\"]', '$argon2id$v=19$m=65536,t=4,p=1$dyFakF7wJbGW37BvgYaXKw$Irl8OK0jOtIwhvhH1WxLSji63WxY8gzZnm/qudbzfEU', 'Léa', 'RICOT', 'F', '1991-09-30', '10 place Maryse Bastié', '37000', 'TOURS', '0600101011', '2021-08-11', 1, '2021-08-11 17:01:45', '2021-08-11 17:01:45'),
(9, 'jean-bonneau@is-corp.fr', '[\"ROLE_EMPLOYEE\"]', '$argon2id$v=19$m=65536,t=4,p=1$xkmaEA8zrotElo1AHif1Qw$5eFqhxSBx4ScpGNY1ZqHPVjEmfxbMAgMioI/2RuIYto', 'Jean', 'BONNEAU', 'M', '1987-11-16', '8bis rue des Vignes', '37400', 'VOUVRAY', '0699118822', '2021-08-11', 1, '2021-08-11 17:06:03', '2021-08-20 11:11:03'),
(10, 'bruno-dagen@is-corp.fr', '[\"ROLE_EMPLOYEE\"]', '$argon2id$v=19$m=65536,t=4,p=1$VqpmaveLrT9bjFA6H/RrTA$SPYOxv4FmfyZj0aVmVmCCQdIcCGIQOuPd+9zKq1vvDk', 'Bruno', 'DAGEN', 'M', '1975-08-06', '101 Boulevard du Maréchal Joffre', '37230', 'FONDETTES', '0699001199', '2021-08-11', 1, '2021-08-11 17:12:24', '2021-08-11 17:12:24');

-- --------------------------------------------------------
--
-- Dumping data for table expertise
--
-- --------------------------------------------------------
INSERT INTO expertise (`id`, `technology_id`, `user_id`, `level`, `appreciated`) VALUES
(2, 1, 5, 4, 1),
(5, 4, 1, 4, 1),
(6, 11, 3, 4, 1),
(7, 1, 10, 3, 1),
(8, 2, 10, 3, 1),
(9, 4, 10, 4, 1),
(10, 9, 10, 4, 1),
(11, 10, 10, 5, 1),
(12, 3, 10, 0, 0),
(13, 3, 9, 5, 1),
(14, 8, 9, 4, 1),
(15, 2, 5, 5, 1),
(16, 3, 5, 4, 0),
(17, 8, 5, 2, 0),
(18, 5, 5, 3, 1),
(19, 7, 5, 5, 1),
(20, 12, 5, 3, 1),
(21, 11, 5, 4, 1),
(22, 4, 5, 0, 0),
(23, 1, 3, 5, 1),
(24, 2, 3, 5, 1),
(25, 3, 3, 2, 1),
(26, 12, 3, 2, 0),
(28, 12, 1, 5, 1),
(29, 1, 9, 5, 1),
(30, 20, 3, 5, 1);

-- --------------------------------------------------------
--
-- Dumping data for table picture
--
-- --------------------------------------------------------
INSERT INTO `picture` (`id`, `user_id`, `name`) VALUES
(1, 2, 'img-marc-hassin-613487f6ce36d.jpg'),
(2, 7, 'img-pacome-laizautre-6134888023dc7.png'),
(3, 3, 'img-annie-mahle-613488fc7d26e.png');

-- --------------------------------------------------------
--
-- Dumping data for table upload
--
-- --------------------------------------------------------
INSERT INTO `upload` (`id`, `name`, `user_id`) VALUES
(1, 'cv-pacome-laizautre-613488979a401.pdf', 7);

-- --------------------------------------------------------
--
-- Dumping data for table experience
--
-- --------------------------------------------------------
INSERT INTO experience (`id`, `user_id`, `profession`, `company_name`, `workplace_town`, `date_start`, `date_end`, `description`) VALUES
(2, 1, 'Data Analyst', 'IS Corp', 'Tours', '2016-02-28', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
(3, 5, 'Toiletteur pour chien', 'Auto-entrepreneur', 'Blois', '2011-03-13', '2013-02-19', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Montes nascetur ridiculus mus mauris vitae ultricies leo integer malesuada. Interdum velit euismod in pellentesque massa placerat duis. Vivamus arcu felis bibendum ut tristique et egestas.'),
(4, 5, 'Développeur web', 'Crédit Agricole', 'Poitiers', '2016-05-16', '2018-03-28', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nisl tincidunt eget nullam non nisi est. Arcu vitae elementum curabitur vitae nunc sed velit dignissim. Id neque aliquam vestibulum morbi blandit cursus risus at ultrices. Ut eu sem integer vitae justo eget magna fermentum iaculis. Amet tellus cras adipiscing enim eu turpis egestas pretium aenean. Turpis nunc eget lorem dolor.'),
(5, 5, 'Concepteur développeur d\'applications', 'Capgemini', 'Tours', '2018-11-19', '2020-02-29', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis viverra nibh cras pulvinar mattis nunc sed blandit libero. Augue lacus viverra vitae congue eu consequat ac felis. Quisque non tellus orci ac auctor. Ac tortor vitae purus faucibus ornare suspendisse sed. Adipiscing elit pellentesque habitant morbi tristique senectus. Et malesuada fames ac turpis egestas sed. Commodo ullamcorper a lacus vestibulum sed arcu non. Lectus proin nibh nisl condimentum id. Quam elementum pulvinar etiam non quam. Odio aenean sed adipiscing diam donec adipiscing tristique. Ornare massa eget egestas purus viverra accumsan. Id nibh tortor id aliquet lectus proin nibh nisl condimentum. Pulvinar neque laoreet suspendisse interdum consectetur libero id faucibus nisl. Pellentesque diam volutpat commodo sed egestas egestas fringilla phasellus.'),
(6, 5, 'Concepteur développeur d\'applications', 'IS Corp', 'Tours', '2018-12-01', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Facilisi cras fermentum odio eu feugiat pretium nibh. Venenatis urna cursus eget nunc scelerisque viverra mauris in aliquam. Turpis egestas maecenas pharetra convallis posuere morbi leo. Aliquet lectus proin nibh nisl condimentum id venenatis a condimentum.'),
(7, 3, 'Web Designer', 'Instant Urbain', 'La Rochelle', '2008-04-03', '2016-09-18', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Facilisi cras fermentum odio eu feugiat pretium nibh. Venenatis urna cursus eget nunc scelerisque viverra mauris in aliquam. Turpis egestas maecenas pharetra convallis posuere morbi leo. Aliquet lectus proin nibh nisl condimentum id venenatis a condimentum.'),
(8, 3, 'Développeuse web', 'IS Corp', 'Tours', '2016-09-26', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lobortis mattis aliquam faucibus purus in massa tempor nec feugiat. A condimentum vitae sapien pellentesque habitant morbi tristique senectus.'),
(9, 8, 'Concepteur Développeur d\'Applications Web', 'IS Corp', 'Tours', '2021-08-11', NULL, 'Toute l\'équipe IS Corp vous souhaite la bienvenue !'),
(10, 9, 'Concepteur développeur d\'applications', 'IS Corp', 'Tours', '2020-10-14', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lobortis mattis aliquam faucibus purus in massa tempor nec feugiat. A condimentum vitae sapien pellentesque habitant morbi tristique senectus.'),
(11, 9, 'Développeur web', 'WorldLine', 'Tours', '2017-03-15', '2020-09-30', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Pellentesque habitant morbi tristique senectus et netus et. Ut consequat semper viverra nam libero justo laoreet sit. Libero justo laoreet sit amet cursus. Quis viverra nibh cras pulvinar. Ullamcorper a lacus vestibulum sed arcu non odio euismod lacinia. Aliquam ut porttitor leo a diam sollicitudin tempor id eu. Eget nullam non nisi est sit amet facilisis magna etiam.'),
(12, 10, 'Développeur web', 'Apside', 'Saint-Pierre-des-Corps', '2016-01-15', '2019-02-18', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Mattis pellentesque id nibh tortor id.'),
(13, 10, 'Développeur web', 'IS Corp', 'Tours', '2019-02-21', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Faucibus vitae aliquet nec ullamcorper sit amet risus nullam. Accumsan sit amet nulla facilisi. Habitant morbi tristique senectus et netus et malesuada fames ac.'),
(14, 8, 'Data Analyst', 'Sopra Steria', 'Nantes', '2018-05-24', '2021-07-22', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');

--
-- Table structure for table `capital_cities`
--

CREATE TABLE `capital_cities` (
  `uid` int(11) NOT NULL auto_increment,
  `country` text NOT NULL,
  `capital` text NOT NULL,
  `lat` float NOT NULL default '0',
  `lng` float NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `lat` (`lat`,`lng`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='lat/lng of worlds capital cities' AUTO_INCREMENT=201 ;

--
-- Dumping data for table `capital_cities`
--

INSERT INTO `capital_cities` VALUES (1, 'Afghanistan', 'Kabul', 34.28, 69.11);
INSERT INTO `capital_cities` VALUES (2, 'Albania', 'Tirane', 41.18, 19.49);
INSERT INTO `capital_cities` VALUES (3, 'Algeria', 'Algiers', 36.42, 3.08);
INSERT INTO `capital_cities` VALUES (4, 'American  Samoa', 'Pago Pago', -14.16, -170.43);
INSERT INTO `capital_cities` VALUES (5, 'Andorra', 'Andorra la Vella', 42.31, 1.32);
INSERT INTO `capital_cities` VALUES (6, 'Angola', 'Luanda', -8.5, 13.15);
INSERT INTO `capital_cities` VALUES (7, 'Antigua and Barbuda', 'W. Indies', 17.2, -61.48);
INSERT INTO `capital_cities` VALUES (8, 'Argentina', 'Buenos Aires', -36.3, -60);
INSERT INTO `capital_cities` VALUES (9, 'Armenia', 'Yerevan', 40.1, 44.31);
INSERT INTO `capital_cities` VALUES (10, 'Aruba', 'Oranjestad', 12.32, -70.02);
INSERT INTO `capital_cities` VALUES (11, 'Australia', 'Canberra', -35.15, 149.08);
INSERT INTO `capital_cities` VALUES (12, 'Austria', 'Vienna', 48.12, 16.22);
INSERT INTO `capital_cities` VALUES (13, 'Azerbaijan', 'Baku', 40.29, 49.56);
INSERT INTO `capital_cities` VALUES (14, 'Bahamas', 'Nassau', 25.05, -77.2);
INSERT INTO `capital_cities` VALUES (15, 'Bahrain', 'Manama', 26.1, 50.3);
INSERT INTO `capital_cities` VALUES (16, 'Bangladesh', 'Dhaka', 23.43, 90.26);
INSERT INTO `capital_cities` VALUES (17, 'Barbados', 'Bridgetown', 13.05, -59.3);
INSERT INTO `capital_cities` VALUES (18, 'Belarus', 'Minsk', 53.52, 27.3);
INSERT INTO `capital_cities` VALUES (19, 'Belgium', 'Brussels', 50.51, 4.21);
INSERT INTO `capital_cities` VALUES (20, 'Belize', 'Belmopan', 17.18, -88.3);
INSERT INTO `capital_cities` VALUES (21, 'Benin', 'Porto-Novo (constitutional cotonou (seat of gvnt)', 6.23, 2.42);
INSERT INTO `capital_cities` VALUES (22, 'Bhutan', 'Thimphu', 27.31, 89.45);
INSERT INTO `capital_cities` VALUES (23, 'Bolivia', 'La Paz (adm.)/sucre (legislative)', -16.2, -68.1);
INSERT INTO `capital_cities` VALUES (24, 'Bosnia and Herzegovina', 'Sarajevo', 43.52, 18.26);
INSERT INTO `capital_cities` VALUES (25, 'Botswana', 'Gaborone', -24.45, 25.57);
INSERT INTO `capital_cities` VALUES (26, 'Brazil', 'Brasilia', -15.47, -47.55);
INSERT INTO `capital_cities` VALUES (27, 'British Virgin Islands', 'Road Town', 18.27, -64.37);
INSERT INTO `capital_cities` VALUES (28, 'Brunei Darussalam', 'Bandar Seri Begawan', 4.52, 115);
INSERT INTO `capital_cities` VALUES (29, 'Bulgaria', 'Sofia', 42.45, 23.2);
INSERT INTO `capital_cities` VALUES (30, 'Burkina Faso', 'Ouagadougou', 12.15, -1.3);
INSERT INTO `capital_cities` VALUES (31, 'Burundi', 'Bujumbura', -3.16, 29.18);
INSERT INTO `capital_cities` VALUES (32, 'Cambodia', 'Phnom Penh', 11.33, 104.55);
INSERT INTO `capital_cities` VALUES (33, 'Cameroon', 'Yaounde', 3.5, 11.35);
INSERT INTO `capital_cities` VALUES (34, 'Canada', 'Ottawa', 45.27, -75.42);
INSERT INTO `capital_cities` VALUES (35, 'Cape Verde', 'Praia', 15.02, -23.34);
INSERT INTO `capital_cities` VALUES (36, 'Cayman Islands', 'George Town', 19.2, -81.24);
INSERT INTO `capital_cities` VALUES (37, 'Central African Republic', 'Bangui', 4.23, 18.35);
INSERT INTO `capital_cities` VALUES (38, 'Chad', 'N''Djamena', 12.1, 14.59);
INSERT INTO `capital_cities` VALUES (39, 'Chile', 'Santiago', -33.24, -70.4);
INSERT INTO `capital_cities` VALUES (40, 'China', 'Beijing', 39.55, 116.2);
INSERT INTO `capital_cities` VALUES (41, 'Colombia', 'Bogota', 4.34, -74);
INSERT INTO `capital_cities` VALUES (42, 'Comros', 'Moroni', -11.4, 43.16);
INSERT INTO `capital_cities` VALUES (43, 'Congo', 'Brazzaville', -4.09, 15.12);
INSERT INTO `capital_cities` VALUES (44, 'Costa Rica', 'San Jose', 9.55, -84.02);
INSERT INTO `capital_cities` VALUES (45, 'Cote d''Ivoire', 'Yamoussoukro', 6.49, -5.17);
INSERT INTO `capital_cities` VALUES (46, 'Croatia', 'Zagreb', 45.5, 15.58);
INSERT INTO `capital_cities` VALUES (47, 'Cuba', 'Havana', 23.08, -82.22);
INSERT INTO `capital_cities` VALUES (48, 'Cyprus', 'Nicosia', 35.1, 33.25);
INSERT INTO `capital_cities` VALUES (49, 'Czech Republic', 'Prague', 50.05, 14.22);
INSERT INTO `capital_cities` VALUES (50, 'Democratic People''s Republic of', 'P''yongyang', 39.09, 125.3);
INSERT INTO `capital_cities` VALUES (51, 'Democratic Republic of the Congo', 'Kinshasa', -4.2, 15.15);
INSERT INTO `capital_cities` VALUES (52, 'Denmark', 'Copenhagen', 55.41, 12.34);
INSERT INTO `capital_cities` VALUES (53, 'Djibouti', 'Djibouti', 11.08, 42.2);
INSERT INTO `capital_cities` VALUES (54, 'Dominica', 'Roseau', 15.2, -61.24);
INSERT INTO `capital_cities` VALUES (55, 'Dominica Republic', 'Santo Domingo', 18.3, -69.59);
INSERT INTO `capital_cities` VALUES (56, 'East Timor', 'Dili', -8.29, 125.34);
INSERT INTO `capital_cities` VALUES (57, 'Ecuador', 'Quito', -0.15, -78.35);
INSERT INTO `capital_cities` VALUES (58, 'Egypt', 'Cairo', 30.01, 31.14);
INSERT INTO `capital_cities` VALUES (59, 'El Salvador', 'San Salvador', 13.4, -89.1);
INSERT INTO `capital_cities` VALUES (60, 'Equatorial Guinea', 'Malabo', 3.45, 8.5);
INSERT INTO `capital_cities` VALUES (61, 'Eritrea', 'Asmara', 15.19, 38.55);
INSERT INTO `capital_cities` VALUES (62, 'Estonia', 'Tallinn', 59.22, 24.48);
INSERT INTO `capital_cities` VALUES (63, 'Ethiopia', 'Addis Ababa', 9.02, 38.42);
INSERT INTO `capital_cities` VALUES (64, 'Falkland Islands (Malvinas)', 'Stanley', -51.4, -59.51);
INSERT INTO `capital_cities` VALUES (65, 'Faroe Islands', 'Torshavn', 62.05, -6.56);
INSERT INTO `capital_cities` VALUES (66, 'Fiji', 'Suva', -18.06, 178.3);
INSERT INTO `capital_cities` VALUES (67, 'Finland', 'Helsinki', 60.15, 25.03);
INSERT INTO `capital_cities` VALUES (68, 'France', 'Paris', 48.5, 2.2);
INSERT INTO `capital_cities` VALUES (69, 'French Guiana', 'Cayenne', 5.05, -52.18);
INSERT INTO `capital_cities` VALUES (70, 'French Polynesia', 'Papeete', -17.32, -149.34);
INSERT INTO `capital_cities` VALUES (71, 'Gabon', 'Libreville', 0.25, 9.26);
INSERT INTO `capital_cities` VALUES (72, 'Gambia', 'Banjul', 13.28, -16.4);
INSERT INTO `capital_cities` VALUES (73, 'Georgia', 'T''bilisi', 41.43, 44.5);
INSERT INTO `capital_cities` VALUES (74, 'Germany', 'Berlin', 52.3, 13.25);
INSERT INTO `capital_cities` VALUES (75, 'Ghana', 'Accra', 5.35, -0.06);
INSERT INTO `capital_cities` VALUES (76, 'Greece', 'Athens', 37.58, 23.46);
INSERT INTO `capital_cities` VALUES (77, 'Greenland', 'Nuuk', 64.1, -51.35);
INSERT INTO `capital_cities` VALUES (78, 'Guadeloupe', 'Basse-Terre', 16, -61.44);
INSERT INTO `capital_cities` VALUES (79, 'Guatemala', 'Guatemala', 14.4, -90.22);
INSERT INTO `capital_cities` VALUES (80, 'Guernsey', 'St. Peter Port', 49.26, -2.33);
INSERT INTO `capital_cities` VALUES (81, 'Guinea', 'Conakry', 9.29, -13.49);
INSERT INTO `capital_cities` VALUES (82, 'Guinea-Bissau', 'Bissau', 11.45, -15.45);
INSERT INTO `capital_cities` VALUES (83, 'Guyana', 'Georgetown', 6.5, -58.12);
INSERT INTO `capital_cities` VALUES (84, 'Haiti', 'Port-au-Prince', 18.4, -72.2);
INSERT INTO `capital_cities` VALUES (85, 'Heard Island and McDonald Islands', '&nbsp;', -53, 74);
INSERT INTO `capital_cities` VALUES (86, 'Honduras', 'Tegucigalpa', 14.05, -87.14);
INSERT INTO `capital_cities` VALUES (87, 'Hungary', 'Budapest', 47.29, 19.05);
INSERT INTO `capital_cities` VALUES (88, 'Iceland', 'Reykjavik', 64.1, -21.57);
INSERT INTO `capital_cities` VALUES (89, 'India', 'New Delhi', 28.37, 77.13);
INSERT INTO `capital_cities` VALUES (90, 'Indonesia', 'Jakarta', -6.09, 106.49);
INSERT INTO `capital_cities` VALUES (91, 'Iran (Islamic Republic of)', 'Tehran', 35.44, 51.3);
INSERT INTO `capital_cities` VALUES (92, 'Iraq', 'Baghdad', 33.2, 44.3);
INSERT INTO `capital_cities` VALUES (93, 'Ireland', 'Dublin', 53.21, -6.15);
INSERT INTO `capital_cities` VALUES (94, 'Israel', 'Jerusalem', 31.71, 35.1);
INSERT INTO `capital_cities` VALUES (95, 'Italy', 'Rome', 41.54, 12.29);
INSERT INTO `capital_cities` VALUES (96, 'Jamaica', 'Kingston', 18, -76.5);
INSERT INTO `capital_cities` VALUES (97, 'Jordan', 'Amman', 31.57, 35.52);
INSERT INTO `capital_cities` VALUES (98, 'Kazakhstan', 'Astana', 51.1, 71.3);
INSERT INTO `capital_cities` VALUES (99, 'Kenya', 'Nairobi', -1.17, 36.48);
INSERT INTO `capital_cities` VALUES (100, 'Kiribati', 'Tarawa', 1.3, 173);
INSERT INTO `capital_cities` VALUES (101, 'Kuwait', 'Kuwait', 29.3, 48);
INSERT INTO `capital_cities` VALUES (102, 'Kyrgyzstan', 'Bishkek', 42.54, 74.46);
INSERT INTO `capital_cities` VALUES (103, 'Lao People''s  Democratic Republic', 'Vientiane', 17.58, 102.36);
INSERT INTO `capital_cities` VALUES (104, 'Latvia', 'Riga', 56.53, 24.08);
INSERT INTO `capital_cities` VALUES (105, 'Lebanon', 'Beirut', 33.53, 35.31);
INSERT INTO `capital_cities` VALUES (106, 'Lesotho', 'Maseru', -29.18, 27.3);
INSERT INTO `capital_cities` VALUES (107, 'Liberia', 'Monrovia', 6.18, -10.47);
INSERT INTO `capital_cities` VALUES (108, 'Libyan Arab Jamahiriya', 'Tripoli', 32.49, 13.07);
INSERT INTO `capital_cities` VALUES (109, 'Liechtenstein', 'Vaduz', 47.08, 9.31);
INSERT INTO `capital_cities` VALUES (110, 'Lithuania', 'Vilnius', 54.38, 25.19);
INSERT INTO `capital_cities` VALUES (111, 'Luxembourg', 'Luxembourg', 49.37, 6.09);
INSERT INTO `capital_cities` VALUES (112, 'Macao, China', 'Macau', 22.12, 113.33);
INSERT INTO `capital_cities` VALUES (113, 'Madagascar', 'Antananarivo', -18.55, 47.31);
INSERT INTO `capital_cities` VALUES (114, 'Malawi', 'Lilongwe', -14, 33.48);
INSERT INTO `capital_cities` VALUES (115, 'Malaysia', 'Kuala Lumpur', 3.09, 101.41);
INSERT INTO `capital_cities` VALUES (116, 'Maldives', 'Male', 4, 73.28);
INSERT INTO `capital_cities` VALUES (117, 'Mali', 'Bamako', 12.34, -7.55);
INSERT INTO `capital_cities` VALUES (118, 'Malta', 'Valletta', 35.54, 14.31);
INSERT INTO `capital_cities` VALUES (119, 'Martinique', 'Fort-de-France', 14.36, -61.02);
INSERT INTO `capital_cities` VALUES (120, 'Mauritania', 'Nouakchott', -20.1, 57.3);
INSERT INTO `capital_cities` VALUES (121, 'Mayotte', 'Mamoudzou', -12.48, 45.14);
INSERT INTO `capital_cities` VALUES (122, 'Mexico', 'Mexico', 19.2, -99.1);
INSERT INTO `capital_cities` VALUES (123, 'Micronesia (Federated States of)', 'Palikir', 6.55, 158.09);
INSERT INTO `capital_cities` VALUES (124, 'Moldova, Republic of', 'Chisinau', 47.02, 28.5);
INSERT INTO `capital_cities` VALUES (125, 'Mozambique', 'Maputo', -25.58, 32.32);
INSERT INTO `capital_cities` VALUES (126, 'Myanmar', 'Yangon', 16.45, 96.2);
INSERT INTO `capital_cities` VALUES (127, 'Namibia', 'Windhoek', -22.35, 17.04);
INSERT INTO `capital_cities` VALUES (128, 'Nepal', 'Kathmandu', 27.45, 85.2);
INSERT INTO `capital_cities` VALUES (129, 'Netherlands', 'Amsterdam/The Hague (seat of Gvnt)', 52.23, 4.54);
INSERT INTO `capital_cities` VALUES (130, 'Netherlands Antilles', 'Willemstad', 12.05, -69);
INSERT INTO `capital_cities` VALUES (131, 'New Caledonia', 'Noumea', -22.17, 166.3);
INSERT INTO `capital_cities` VALUES (132, 'New Zealand', 'Wellington', -41.19, 174.46);
INSERT INTO `capital_cities` VALUES (133, 'Nicaragua', 'Managua', 12.06, -86.2);
INSERT INTO `capital_cities` VALUES (134, 'Niger', 'Niamey', 13.27, 2.06);
INSERT INTO `capital_cities` VALUES (135, 'Nigeria', 'Abuja', 9.05, 7.32);
INSERT INTO `capital_cities` VALUES (136, 'Norfolk Island', 'Kingston', -45.2, 168.43);
INSERT INTO `capital_cities` VALUES (137, 'Northern Mariana Islands', 'Saipan', 15.12, 145.45);
INSERT INTO `capital_cities` VALUES (138, 'Norway', 'Oslo', 59.55, 10.45);
INSERT INTO `capital_cities` VALUES (139, 'Oman', 'Masqat', 23.37, 58.36);
INSERT INTO `capital_cities` VALUES (140, 'Pakistan', 'Islamabad', 33.4, 73.1);
INSERT INTO `capital_cities` VALUES (141, 'Palau', 'Koror', 7.2, 134.28);
INSERT INTO `capital_cities` VALUES (142, 'Panama', 'Panama', 9, -79.25);
INSERT INTO `capital_cities` VALUES (143, 'Papua New Guinea', 'Port Moresby', -9.24, 147.08);
INSERT INTO `capital_cities` VALUES (144, 'Paraguay', 'Asuncion', -25.1, -57.3);
INSERT INTO `capital_cities` VALUES (145, 'Peru', 'Lima', -12, -77);
INSERT INTO `capital_cities` VALUES (146, 'Philippines', 'Manila', 14.4, 121.03);
INSERT INTO `capital_cities` VALUES (147, 'Poland', 'Warsaw', 52.13, 21);
INSERT INTO `capital_cities` VALUES (148, 'Portugal', 'Lisbon', 38.42, -9.1);
INSERT INTO `capital_cities` VALUES (149, 'Puerto Rico', 'San Juan', 18.28, -66.07);
INSERT INTO `capital_cities` VALUES (150, 'Qatar', 'Doha', 25.15, 51.35);
INSERT INTO `capital_cities` VALUES (151, 'Republic of  Korea', 'Seoul', 37.31, 126.58);
INSERT INTO `capital_cities` VALUES (152, 'Romania', 'Bucuresti', 44.27, 26.1);
INSERT INTO `capital_cities` VALUES (153, 'Russian Federation', 'Moskva', 55.45, 37.35);
INSERT INTO `capital_cities` VALUES (154, 'Rawanda', 'Kigali', -1.59, 30.04);
INSERT INTO `capital_cities` VALUES (155, 'Saint Kitts and Nevis', 'Basseterre', 17.17, -62.43);
INSERT INTO `capital_cities` VALUES (156, 'Saint Lucia', 'Castries', 14.02, -60.58);
INSERT INTO `capital_cities` VALUES (157, 'Saint Pierre and Miquelon', 'Saint-Pierre', 46.46, -56.12);
INSERT INTO `capital_cities` VALUES (158, 'Saint vincent and the Greenadines', 'Kingstown', 13.1, -61.1);
INSERT INTO `capital_cities` VALUES (159, 'Samoa', 'Apia', -13.5, -171.5);
INSERT INTO `capital_cities` VALUES (160, 'San Marino', 'San Marino', 43.55, 12.3);
INSERT INTO `capital_cities` VALUES (161, 'Sao Tome and Principe', 'Sao Tome', 0.1, 6.39);
INSERT INTO `capital_cities` VALUES (162, 'Saudi Arabia', 'Riyadh', 24.41, 46.42);
INSERT INTO `capital_cities` VALUES (163, 'Senegal', 'Dakar', 14.34, -17.29);
INSERT INTO `capital_cities` VALUES (164, 'Sierra Leone', 'Freetown', 8.3, -13.17);
INSERT INTO `capital_cities` VALUES (165, 'Slovakia', 'Bratislava', 48.1, 17.07);
INSERT INTO `capital_cities` VALUES (166, 'Slovenia', 'Ljubljana', 46.04, 14.33);
INSERT INTO `capital_cities` VALUES (167, 'Solomon Islands', 'Honiara', -9.27, 159.57);
INSERT INTO `capital_cities` VALUES (168, 'Somalia', 'Mogadishu', 2.02, 45.25);
INSERT INTO `capital_cities` VALUES (169, 'South Africa', 'Pretoria (adm.) / Cap Town (Legislative) / Bloemfontein (Judicial)', -25.44, 28.12);
INSERT INTO `capital_cities` VALUES (170, 'Spain', 'Madrid', 40.25, -3.45);
INSERT INTO `capital_cities` VALUES (171, 'Sudan', 'Khartoum', 15.31, 32.35);
INSERT INTO `capital_cities` VALUES (172, 'Suriname', 'Paramaribo', 5.5, -55.1);
INSERT INTO `capital_cities` VALUES (173, 'Swaziland', 'Mbabane (Adm.)', -26.18, 31.06);
INSERT INTO `capital_cities` VALUES (174, 'Sweden', 'Stockholm', 59.2, 18.03);
INSERT INTO `capital_cities` VALUES (175, 'Switzerland', 'Bern', 46.57, 7.28);
INSERT INTO `capital_cities` VALUES (176, 'Syrian Arab Republic', 'Damascus', 33.3, 36.18);
INSERT INTO `capital_cities` VALUES (177, 'Tajikistan', 'Dushanbe', 38.33, 68.48);
INSERT INTO `capital_cities` VALUES (178, 'Thailand', 'Bangkok', 13.45, 100.35);
INSERT INTO `capital_cities` VALUES (179, 'The Former Yugoslav Republic of Macedonia', 'Skopje', 42.01, 21.26);
INSERT INTO `capital_cities` VALUES (180, 'Togo', 'Lome', 6.09, 1.2);
INSERT INTO `capital_cities` VALUES (181, 'Tonga', 'Nuku''alofa', -21.1, -174);
INSERT INTO `capital_cities` VALUES (182, 'Tunisia', 'Tunis', 36.5, 10.11);
INSERT INTO `capital_cities` VALUES (183, 'Turkey', 'Ankara', 39.57, 32.54);
INSERT INTO `capital_cities` VALUES (184, 'Turkmenistan', 'Ashgabat', 38, 57.5);
INSERT INTO `capital_cities` VALUES (185, 'Tuvalu', 'Funafuti', -8.31, 179.13);
INSERT INTO `capital_cities` VALUES (186, 'Uganda', 'Kampala', 0.2, 32.3);
INSERT INTO `capital_cities` VALUES (187, 'Ukraine', 'Kiev (Rus)', 50.3, 30.28);
INSERT INTO `capital_cities` VALUES (188, 'United Arab Emirates', 'Abu Dhabi', 24.28, 54.22);
INSERT INTO `capital_cities` VALUES (189, 'United Kingdom of Great Britain and Northern Ireland', 'London', 51.36, -0.05);
INSERT INTO `capital_cities` VALUES (190, 'United  Republic of Tanzania', 'Dodoma', -6.08, 35.45);
INSERT INTO `capital_cities` VALUES (191, 'United States', 'Washington DC', 39.91, -77.02);
INSERT INTO `capital_cities` VALUES (192, 'United States', 'Charlotte Amalie', 18.21, -64.56);
INSERT INTO `capital_cities` VALUES (193, 'Uruguay', 'Montevideo', -34.5, -56.11);
INSERT INTO `capital_cities` VALUES (194, 'Uzbekistan', 'Tashkent', 41.2, 69.1);
INSERT INTO `capital_cities` VALUES (195, 'Vanuatu', 'Port-Vila', -17.45, 168.18);
INSERT INTO `capital_cities` VALUES (196, 'Venezuela', 'Caracas', 10.3, -66.55);
INSERT INTO `capital_cities` VALUES (197, 'Viet Nam', 'Hanoi', 21.05, 105.55);
INSERT INTO `capital_cities` VALUES (198, 'Yugoslavia', 'Belgrade', 44.5, 20.37);
INSERT INTO `capital_cities` VALUES (199, 'Zambia', 'Lusaka', -15.28, 28.16);
INSERT INTO `capital_cities` VALUES (200, 'Zimbabwe', 'Harare', -17.43, 31.02);
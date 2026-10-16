-- Données FICTIVES. Les dates sont relatives à aujourd'hui (CURDATE())
-- pour qu'il y ait toujours des audits passés, en retard et à venir.
USE seac_audits;

INSERT INTO sites (nom, ville) VALUES
('Usine Garonne',    'Toulouse'),
('Usine Lauragais',  'Castelnaudary'),
('Usine Ariège',     'Pamiers'),
('Usine Tarn',       'Albi'),
('Usine Gers',       'Auch'),
('Usine Aveyron',    'Rodez'),
('Usine Lot',        'Cahors'),
('Usine Landes',     'Mont-de-Marsan'),
('Usine Hérault',    'Béziers'),
('Usine Gironde',    'Libourne');

INSERT INTO audits (site_id, date_prevue, statut, type_audit, commentaire) VALUES
(1,  CURDATE() - INTERVAL 60 DAY, 'realise',     'Sécurité',      'RAS'),
(1,  CURDATE() - INTERVAL 5 DAY,  'planifie',    'Qualité',       NULL),
(1,  CURDATE() + INTERVAL 30 DAY, 'a_planifier', 'Environnement', NULL),
(2,  CURDATE() - INTERVAL 45 DAY, 'realise',     'Qualité',       '2 écarts mineurs'),
(2,  CURDATE() + INTERVAL 7 DAY,  'planifie',    'Sécurité',      NULL),
(2,  CURDATE() - INTERVAL 12 DAY, 'a_planifier', 'Maintenance',   'Auditeur non disponible'),
(3,  CURDATE() - INTERVAL 90 DAY, 'realise',     'Environnement', NULL),
(3,  CURDATE() + INTERVAL 14 DAY, 'planifie',    'Qualité',       NULL),
(3,  CURDATE() + INTERVAL 60 DAY, 'a_planifier', 'Sécurité',      NULL),
(4,  CURDATE() - INTERVAL 20 DAY, 'planifie',    'Sécurité',      'Reporté deux fois'),
(4,  CURDATE() + INTERVAL 3 DAY,  'planifie',    'Qualité',       NULL),
(4,  CURDATE() - INTERVAL 30 DAY, 'realise',     'Maintenance',   NULL),
(5,  CURDATE() + INTERVAL 21 DAY, 'a_planifier', 'Qualité',       NULL),
(5,  CURDATE() - INTERVAL 10 DAY, 'realise',     'Sécurité',      'Plan d''action en cours'),
(5,  CURDATE() + INTERVAL 45 DAY, 'a_planifier', 'Environnement', NULL),
(6,  CURDATE() - INTERVAL 2 DAY,  'planifie',    'Environnement', NULL),
(6,  CURDATE() + INTERVAL 10 DAY, 'planifie',    'Sécurité',      NULL),
(6,  CURDATE() - INTERVAL 75 DAY, 'realise',     'Qualité',       NULL),
(7,  CURDATE() + INTERVAL 5 DAY,  'planifie',    'Maintenance',   NULL),
(7,  CURDATE() - INTERVAL 35 DAY, 'a_planifier', 'Sécurité',      'Date à confirmer avec le chef d''usine'),
(7,  CURDATE() + INTERVAL 90 DAY, 'a_planifier', 'Qualité',       NULL),
(8,  CURDATE() - INTERVAL 15 DAY, 'realise',     'Environnement', NULL),
(8,  CURDATE() + INTERVAL 20 DAY, 'planifie',    'Qualité',       NULL),
(8,  CURDATE() + INTERVAL 1 DAY,  'planifie',    'Sécurité',      NULL),
(9,  CURDATE() - INTERVAL 8 DAY,  'planifie',    'Maintenance',   NULL),
(9,  CURDATE() + INTERVAL 40 DAY, 'a_planifier', 'Sécurité',      NULL),
(9,  CURDATE() - INTERVAL 50 DAY, 'realise',     'Qualité',       NULL),
(10, CURDATE() + INTERVAL 12 DAY, 'planifie',    'Environnement', NULL),
(10, CURDATE() - INTERVAL 25 DAY, 'realise',     'Sécurité',      NULL),
(10, CURDATE() + INTERVAL 70 DAY, 'a_planifier', 'Maintenance',   NULL);

CREATE TABLE `ADDROBJ` (
  `aoguid` varchar(36) NOT NULL COMMENT 'Глобальный уникальный идентификатор адресного объекта',
  `formalname` varchar(120) NOT NULL COMMENT 'Формализованное наименование',
  `regioncode` varchar(2) NOT NULL COMMENT 'Код региона',
  `autocode` char(1) NOT NULL COMMENT 'Код автономии',
  `areacode` varchar(3) NOT NULL COMMENT 'Код района',
  `citycode` varchar(3) NOT NULL COMMENT 'Код города',
  `ctarcode` varchar(3) NOT NULL COMMENT 'Код внутригородского района',
  `placecode` varchar(3) NOT NULL COMMENT 'Код населенного пункта',
  `plancode` varchar(4) NOT NULL COMMENT 'Код элемента планировочной структуры',
  `streetcode` varchar(4) NOT NULL COMMENT 'Код улицы',
  `extrcode` varchar(4) NOT NULL COMMENT 'Код дополнительного адресообразующего элемента',
  `sextcode` varchar(3) NOT NULL COMMENT 'Код подчиненного дополнительного адресообразующего элемента',
  `offname` varchar(120) DEFAULT NULL COMMENT 'Официальное наименование',
  `postalcode` char(6) DEFAULT NULL COMMENT 'Почтовый индекс',
  `ifnsfl` varchar(4) DEFAULT NULL COMMENT 'Код ИФНС ФЛ',
  `terrifnsfl` varchar(4) DEFAULT NULL COMMENT 'Код территориального участка ИФНС ФЛ',
  `ifnsul` varchar(4) DEFAULT NULL COMMENT 'Код ИФНС ЮЛ',
  `terrifnsul` varchar(4) DEFAULT NULL COMMENT 'Код территориального участка ИФНС ЮЛ',
  `okato` varchar(11) DEFAULT NULL COMMENT 'ОКАТО',
  `oktmo` varchar(11) DEFAULT NULL COMMENT 'ОКТМО',
  `updatedate` date NOT NULL COMMENT 'Дата  внесения записи',
  `shortname` varchar(10) NOT NULL COMMENT 'Краткое наименование типа объекта',
  `aolevel` int(10) unsigned NOT NULL COMMENT 'Уровень адресного объекта ',
  `parentguid` char(36) DEFAULT NULL COMMENT 'Идентификатор объекта родительского объекта',
  `aoid` char(36) NOT NULL COMMENT 'Уникальный идентификатор записи',
  `previd` varchar(36) DEFAULT NULL COMMENT 'Идентификатор записи связывания с предыдушей исторической записью',
  `nextid` varchar(36) DEFAULT NULL COMMENT 'Идентификатор записи  связывания с последующей исторической записью',
  `code` varchar(17) DEFAULT NULL COMMENT 'Код адресного объекта одной строкой с признаком актуальности из КЛАДР 4.0',
  `plaincode` varchar(15) DEFAULT NULL COMMENT 'Код адресного объекта из КЛАДР 4.0 одной строкой без признака актуальности (последних двух цифр)',
  `actstatus` tinyint(1) unsigned NOT NULL COMMENT 'Статус актуальности адресного объекта ФИАС. Актуальный адрес на текущую дату. Обычно последняя запись об адресном объекте.',
  `livestatus` tinyint(1) unsigned NOT NULL COMMENT 'Статус актуальности адресного объекта ФИАС на текущую дату: 0 – Не актуальный 1 - Актуальный',
  `centstatus` int(10) unsigned NOT NULL COMMENT 'Статус центра',
  `operstatus` int(10) unsigned NOT NULL COMMENT 'Статус действия над записью – причина появления записи',
  `currstatus` int(10) unsigned NOT NULL COMMENT 'Статус актуальности КЛАДР 4 (последние две цифры в коде)',
  `startdate` date NOT NULL COMMENT 'Начало действия записи',
  `enddate` date NOT NULL COMMENT 'Окончание действия записи',
  `normdoc` varchar(36) DEFAULT NULL COMMENT 'Внешний ключ на нормативный документ',
  `cadnum` varchar(100) NOT NULL COMMENT 'Кадастровый номер',
  `divtype` int(1) unsigned NOT NULL COMMENT 'Тип деления: 0 – не определено 1 – муниципальное 2 – административное',
  PRIMARY KEY (`aoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Классификатор адресообразующих элементов';


CREATE TABLE `SOCRBASE` (
  `level` varchar(5) NOT NULL COMMENT 'Уровень адресного объекта',
  `scname` varchar(10) DEFAULT NULL COMMENT 'Краткое наименование типа объекта',
  `socrname` varchar(50) NOT NULL COMMENT 'Полное наименование типа объекта',
  `kod_t_st` varchar(4) NOT NULL COMMENT 'Ключевое поле',
  PRIMARY KEY (`kod_t_st`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Тип адресного объекта';

CREATE TABLE IF NOT EXISTS `fias_index` (
  `aoguid` varchar(36) NOT NULL COMMENT 'Глобальный уникальный идентификатор адресного объекта',
  `aolevel` int(10) unsigned NOT NULL COMMENT 'Уровень адресного объекта ',
  `scname` varchar(10) NOT NULL COMMENT 'Краткое наименование типа объекта',
  `fullname` varchar(255) NOT NULL COMMENT 'Полное наименование типа объекта',
  PRIMARY KEY (`aoguid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Индекс';

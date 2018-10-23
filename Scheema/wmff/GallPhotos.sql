CREATE TABLE `GallPhotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Galid` int(11) NOT NULL,
  `Credit` text NOT NULL,
  `File` text NOT NULL,
  `Caption` text NOT NULL,
  `RelOrder` int(11) NOT NULL,
  `ImageHeight` int(11) NOT NULL,
  `ImageWidth` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*
 Navicat MySQL Data Transfer

 Source Server         : test.bd
 Source Server Type    : MySQL
 Source Server Version : 80034 (8.0.34-cll-lve)
 Source Host           : vinculamos.cl:3306
 Source Schema         : vinculam_losleones

 Target Server Type    : MySQL
 Target Server Version : 80034 (8.0.34-cll-lve)
 File Encoding         : 65001

 Date: 21/08/2023 18:21:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for actividades
-- ----------------------------
DROP TABLE IF EXISTS `actividades`;
CREATE TABLE `actividades` (
  `acti_codigo` int NOT NULL AUTO_INCREMENT,
  `acti_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `acti_fecha` datetime NOT NULL,
  `acti_acuerdos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `acti_fecha_cumplimiento` datetime NOT NULL,
  `acti_avance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `acti_creado` timestamp NULL DEFAULT NULL,
  `acti_actualizado` timestamp NULL DEFAULT NULL,
  `acti_visible` int NOT NULL DEFAULT '1',
  `acti_nickname_mod` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `acti_rol_mod` int DEFAULT NULL,
  PRIMARY KEY (`acti_codigo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for ambito
-- ----------------------------
DROP TABLE IF EXISTS `ambito`;
CREATE TABLE `ambito` (
  `amb_codigo` int NOT NULL AUTO_INCREMENT,
  `amb_nombre` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `amb_descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `amb_director` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `amb_tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amb_creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amb_actualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amb_visible` int DEFAULT '1',
  `amb_nickname_mod` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `amb_rol_mod` int DEFAULT NULL,
  PRIMARY KEY (`amb_codigo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for ambito_accion
-- ----------------------------
DROP TABLE IF EXISTS `ambito_accion`;
CREATE TABLE `ambito_accion` (
  `amac_codigo` int NOT NULL AUTO_INCREMENT,
  `amac_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amac_descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `amac_director` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amac_creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amac_actualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amac_visible` int DEFAULT '1',
  `amac_nickname_mod` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amac_rol_mod` int DEFAULT NULL,
  PRIMARY KEY (`amac_codigo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

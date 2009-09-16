#!/usr/bin/php -q
<?
/**
 * doxyphp v0.1b - Doxygen INPUT_FILTER for PHP.
 * Copyright (C) 2007 Sergey V. Skuntcev <skuntcev@gmail.com>
 * 
 * Doxyfile:
 * INPUT_FILTER           = "/usr/bin/php doxyphp.php"
 * FILTER_SOURCE_FILES    = YES
 */

if (!defined('T_ML_COMMENT')) define('T_ML_COMMENT', T_COMMENT);
else define('T_DOC_COMMENT', T_ML_COMMENT);

if (isset($_SERVER["SERVER_NAME"])) $is_cli = false;
else $is_cli = true;

if ($is_cli) {
	if (!isset($argv[1]) || !is_file($argv[1])) {
		print "\n".
			"doxyphp v0.1b - Doxygen INPUT_FILTER for PHP.\n".
			"Copyright (C) 2007 Sergey V. Skuntcev <skuntcev@gmail.com>\n".
			"\n".
			"Usage: doxyphp.php filename\n".
			"Pre-processes PHP code in file <filename> and outputs".
			" something doxygen does understand.\n".
			"\n";
		exit(1);
	}
	$source = file_get_contents($argv[1]);
	print "<?/*doxyphp v0.1b*/?>";
	$source = Doxyphp::process($source);
	print $source;
} else {
	if (is_file($fn = "doxyphp_example.php"))
		$source = file_get_contents($fn);
	else
		$source = file_get_contents(__FILE__);
	
	ob_start();
	
	if (isset($_GET["source"]))
		print $source;
	elseif (isset($_GET["pseudo"]))
		Doxyphp::debug_pseudo($source);
	elseif (isset($_GET["tokens"]))
		Doxyphp::debug_tokens($source);
	else {
		$source = Doxyphp::process($source);
		print $source;
	}
		
	$source = ob_get_contents();
	ob_end_clean();
	highlight_string($source);
}

///////////////////////////////////////////////////////////////////////////////
// clases /////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
/**
 * Config
 */
class DoxyphpConfig {
	// если TRUE выставляем у не задокументированых переменных тип undefined
	public static $undefined = 0;
	// если TRUE убиваем бакс у всех переменных
	public static $baks = 1;
	// все "$var = " в коде преобразуются в "LN::$var = ". если FALSE не заменяем.
	public static $localnamespace = "LN::";
	// замена через preg_replace по псевдо коду с вырезанными коментами и строками
	// не должно быть ничего что фатально портит синтаксис с точки зрения token_get_all()
	public static $replace = array(
		"patterns" => array(
			// чтоб доксиген не делал протокол (при этом не прослеживается наследовение)
			'/abstract\s+(class)/i',
			// убиваем часто используемые методы, чтоб убить лишнии связи
			'/((\:\:|\-\>)raiseError|(\:\:|\-\>)_raiseError|(\:\:|\-\>)isError|(\:\:|\-\>)getCode)\(/i',
			'/PEAR\:\:/i',
		),
		"replacements" => array(
			'\1',
			'Cut1(',
			'Cut2->',
		)
	);
}

/**
 * Doxyphp
 */
class Doxyphp {
	protected static
		$source,
		$psource,
		$psourceVars,
		$tokens;
	
	public static function process($source) {
		self::$source = $source;
		
		///////////////////////////////////////////////////////////////////////////////
		// работаем с tokens //////////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////
		self::$tokens = DoxyphpTokenizer::makeTokens($source);
		
		// Если заданы замены, производит их.
		self::rule1();
		
		///////////////////////////////////////////////////////////////////////////////
		// работаем с pseudosource ////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////
		list(self::$psource, self::$psourceVars) = DoxyphpTokenizer::makePseudoSource(self::$tokens);
		
		// Заменяет "self::" и "parent::" на имена классов.
		self::rule2();
		// Прописывает типы переменных и констант класса по тэгу var.
		self::rule3();
		// По описанию функции прописывает возвращаемый тип (тэг return)
		//  и типы параметров (param)
		self::rule4();
		// Заменяет ZendStudio-описание типа объекта (var object class)
	 	//  на строку кода: "Type Object;" - типа определение переменной в Си.
		self::rule5();
		// Убивает у всех переменных бакс. Если DoxyphpConfig::$baks true.
		self::rule6();
		// Заменяет определение глобальных констант в стиле PHP (define)
	 	//  на стиль Си: "const CONST = VALUE;"
		self::rule7();
		// Проставляет у всех переменных при присваивании значения префикс ("LN::").
		//  Чтоб доксиген не воспринимал их как глобальные и не делал перекрестные ссылки.
		self::rule8();
		// Заменяет тэг package на addtogroup.
	 	//  Чтоб при обработке кода заточенного под PhpDocumentor доксиген не делал namespace из пакета.
		self::rule9();
		
		$source = DoxyphpTokenizer::makeSourceByPseudo(self::$psource, self::$psourceVars);
		return $source;
	}
	
	/**
	 * Если заданы замены, производит их.
	 */
	protected static function rule1() {
		if (is_array(DoxyphpConfig::$replace)) {
			$cut_tokens = array(T_COMMENT, T_ML_COMMENT, T_DOC_COMMENT, T_CONSTANT_ENCAPSED_STRING);
			list(self::$psource, self::$psourceVars) = 
				DoxyphpTokenizer::makePseudoSource(self::$tokens, $cut_tokens, false, false);
			
			self::$psource = preg_replace(DoxyphpConfig::$replace["patterns"], DoxyphpConfig::$replace["replacements"], self::$psource);
			
			self::$source = DoxyphpTokenizer::makeSourceByPseudo(self::$psource, self::$psourceVars);
			self::$tokens = DoxyphpTokenizer::makeTokens(self::$source);
		}
	}
	
	/**
	 * Заменяет "self::" и "parent::" на имена классов.
	 */
	protected static function rule2() {
		global $argv;
		// делим через объявления классов
		$regex = '/class\s+(T_STRING__\d+)(?:\s+(extends)\s+(T_STRING__\d+))?/i';
		$res = preg_split($regex, self::$psource, null, PREG_SPLIT_DELIM_CAPTURE);
		for ($i=1, $ci=sizeof($res); $i<$ci; $i++) {
			$self = self::$psourceVars[$res[$i]];
			if ("extends"==strtolower($res[$i+1])) {
				$parent = self::$psourceVars[$res[$i+2]];
				$i+=2;
			} else $parent = "parent";
			// ещем все T_STRING::, и если это self/parent заменяем
			if (preg_match_all('/(T_STRING__\d+)\:\:/', $res[$i+1], $m, PREG_SET_ORDER)) {
				for ($j=0, $cj=sizeof($m); $j<$cj; $j++) {
					$str = strtolower(self::$psourceVars[$m[$j][1]]);
					if ("self"===$str)
						self::$psourceVars[$m[$j][1]] = $self;
					elseif ("parent"===$str)
						self::$psourceVars[$m[$j][1]] = $parent;
				}
			}
			$i++;
		}
	}
	
	/**
	 * Прописывает типы переменных и констант класса по тэгу var.
	 */
	protected static function rule3() {
		// ищем св-ва класса
		$regex = '/(T_DOC_COMMENT__\d+|T_COMMENT__\d+)?\s*T_SCOPE__\d+(?:\s+T_SCOPE__\d+)*\s+(T_VARIABLE__\d+|T_STRING__\d+)/';
		if (preg_match_all($regex, self::$psource, $m, PREG_SET_ORDER)) {
			for ($i=0, $ci=sizeof($m); $i<$ci; $i++) {
				if (DoxyphpConfig::$undefined) $type = "undefined";
				else $type = false;
				if (!empty($m[$i][1])) {
					$docblock = self::$psourceVars[$m[$i][1]];
					$tags = DoxyphpDocblockParser::process($docblock);
					if (isset($tags["var"])) {
						$type = $tags["var"]["type"];
						$docblock = str_replace($tags["var"]["str"], "", $docblock);
						self::$psourceVars[$m[$i][1]] = $docblock;
					}
				}
				if ($type) {
					$str = str_replace($m[$i][2], $type." ".$m[$i][2], $m[$i][0]);
					self::$psource = str_replace($m[$i][0], $str, self::$psource);
				}
			}
		}
	}
	
	/**
	 * По описанию функции прописывает возвращаемый тип (тэг return)
	 *  и типы параметров (param)
	 */
	protected static function rule4() {
		// ищем методы/функции
		$regex = '/(T_DOC_COMMENT__\d+|T_COMMENT__\d+)?\s*(?:T_SCOPE__\d+(?:\s+T_SCOPE__\d+)*\s+)?function\s+((\&)?\s*T_STRING__\d+)\s*\((.*?)\)\s*[\{;]/is';
		if (preg_match_all($regex, self::$psource, $m, PREG_SET_ORDER)) {
			for ($i=0, $ci=sizeof($m); $i<$ci; $i++) {
				$func = $m[$i][0];
				$vars = $tags = array();
				
				// выбираем из докблока описания переменных
				if (!empty($m[$i][1])) {
					$docblock = self::$psourceVars[$m[$i][1]];
					$tags = DoxyphpDocblockParser::process($docblock);
					if (isset($tags["params"]))
						$vars = $tags["params"];
				}
				
				// проставляем тип ретурна
				if (isset($tags["return"])) {
					$type = $tags["return"]["type"].$m[$i][3];
					// убиваем в докблоке тип
					$str = str_replace($type, "", $tags["return"]["str"]);
					$docblock = str_replace($tags["return"]["str"], $str, $docblock);
				} else
					$type = "void".$m[$i][3];
				$func = str_ireplace("function", "function ".$type, $func);
				if ($m[$i][3])
					$func = str_replace($m[$i][2], substr($m[$i][2], 1), $func);
				
				// выбираем параметры функции
				if (preg_match_all('/(T_STRING__\d+\s+)?(\&)?(T_VARIABLE__\d+)/', $m[$i][4], $m2, PREG_SET_ORDER)) {
					for ($j=0, $cj=sizeof($m2); $j<$cj; $j++) {
						$var = self::$psourceVars[$m2[$j][3]];
						// добавляем тип в строку функции
						if (empty($m2[$j][1])) {
							if (isset($vars[$var])) $type = $vars[$var]["type"].$m2[$j][2]." ";
							elseif (DoxyphpConfig::$undefined) $type = "undefined".$m2[$j][2]." ";
							else $type = $m2[$j][2];
							$func = str_replace($m2[$j][0], $type.$m2[$j][3], $func);
						}
						// убиваем в докблоке тип
						if (isset($vars[$var])) {
							$str = str_replace($vars[$var]["type"], "", $vars[$var]["str"]);
							if (DoxyphpConfig::$baks)
								$str = str_replace("$", "", $str);
							$docblock = str_replace($vars[$var]["str"], $str, $docblock);
						}
					}
				}
				
				// заменяем докблок
				if (!empty($m[$i][1]))
					self::$psourceVars[$m[$i][1]] = $docblock;
				
				// заменяем строку функции
				self::$psource = str_replace($m[$i][0], $func, self::$psource);
			}
		}
	}
	
	/**
	 * Заменяет ZendStudio-описание типа объекта (var object class)
	 *  на строку кода: "Type Object;" - типа определение переменной в Си.
	 */
	protected static function rule5() {
		foreach (self::$psourceVars as $name=>$v) {
			if ("T_COMMENT__"===substr($name, 0, 11)) {
				$tags = DoxyphpDocblockParser::process($v, true);
				if (isset($tags["var"])) {
					if (DoxyphpConfig::$baks)
						$tags["var"]["var"] = substr($tags["var"]["var"], 1);
					self::$psourceVars[$name] = $tags["var"]["type"]." ".$tags["var"]["var"].";";
				}
			}
		}
	}
	
	/**
	 * Убивает у всех переменных бакс. Если DoxyphpConfig::$baks true.
	 */
	protected static function rule6() {
		if (DoxyphpConfig::$baks) {
			foreach (self::$psourceVars as $name=>$v) {
				if ("T_VARIABLE__"===substr($name, 0, 12))
					self::$psourceVars[$name] = substr($v, 1);
			}
		}
	}
	
	/**
	 * Заменяет определение глобальных констант в стиле PHP (define)
	 *  на стиль Си: "const CONST = VALUE;"
	 */
	protected static function rule7() {
		$regex = '/define\s*\(\s*(T_CONSTANT_ENCAPSED_STRING__\d+)\s*,\s*(.+?)\)\s*;/i';
		if (preg_match_all($regex, self::$psource, $m, PREG_SET_ORDER)) {
			self::$psource = preg_replace($regex, 'const \1 = \2;', self::$psource);
			for ($i=0, $ci=sizeof($m); $i<$ci; $i++) {
				$const = self::$psourceVars[$m[$i][1]];
				$const = substr($const, 1, -1);
				self::$psourceVars[$m[$i][1]] = $const;
			}
		}
	}
	
	/**
	 * Проставляет у всех переменных при присваивании значения префикс ("LN::").
	 *  Чтоб доксиген не воспринимал их как глобальные и не делал перекрестные ссылки.
	 */
	protected static function rule8() {
		if (DoxyphpConfig::$localnamespace) {
			$regex = '/((?:T_SCOPE__\d+|\:\:|\-\>|function)[^;]*)?(T_VARIABLE__\d+(?:\s*\[\])?\s*\=)/';
			self::$psource = preg_replace_callback($regex, array("Doxyphp", "rule8_callback"), self::$psource);
		}
	}
	private static function rule8_callback($m) {
		if (empty($m[1]))
			return DoxyphpConfig::$localnamespace.$m[2];
		return $m[1].$m[2];
	}
	
	/**
	 * Заменяет тэг package на addtogroup.
	 *  Чтоб при обработке кода заточенного под PhpDocumentor доксиген не делал namespace из пакета.
	 */
	protected static function rule9() {
		foreach (self::$psourceVars as $name=>$v) {
			if (false!==strpos($name, "_COMMENT"))
				self::$psourceVars[$name] = str_replace("@package", "@addtogroup", $v);
		}
	}
	
	public function debug_pseudo($source) {
		$tokens = DoxyphpTokenizer::makeTokens($source);
		list($psource, $psourceVars) = DoxyphpTokenizer::makePseudoSource($tokens);
		print $psource;
		print "\n\n";
		print_r($psourceVars);
	}
	
	public function debug_tokens($source) {
		$tokens = DoxyphpTokenizer::makeTokens($source);
		for ($i=0, $ci=sizeof($tokens); $i<$ci; $i++) {
			print str_pad($i, 4, "0", STR_PAD_LEFT).": ";
			if (is_array($tokens[$i]))
				print token_name($tokens[$i][0])." => ".$tokens[$i][1]."\n";
			else
				print $tokens[$i]."\n";
		}
	}
}

/**
 * Парсер док-блоков.
 */
class DoxyphpDocblockParser {
	protected static
		$tags  = "var|param|return",
		$r_string = '[a-z0-9_]+',
		$r_type = '(?:[a-z0-9_]+(?:\[\])?|array[ \t]+of[ \t]+[a-z0-9_]+)';
	
	public static function process($docblock, $is_var_comment=false) {
		// убираем обрамляющие "/**", "/*", "*", "*/" 
		if ($is_var_comment)
			$regex = '/((^\/\*[ ]?)|([ ]?\*\/$))?/m';
		else
			$regex = '/^[ \t]*(?:(?:\/\*\*)|\*\/|\*)[ ]?/m';
		$docblock = preg_replace($regex, '', $docblock);
		
		// тип может быть вида: "type1|type2"
		$r_type = self::$r_type.'(?:\|'.self::$r_type.')*';
		
		// разбиваем по тагам
		$res = preg_split('/\@('.self::$tags.')/', $docblock, null, PREG_SPLIT_DELIM_CAPTURE);
		
		// выбираем типы и имена для каждого тага
		$tags = array();
		for ($i=1, $ci=sizeof($res); $i<$ci; $i++) {
			if ($is_var_comment) {
				$regex = '/^[ \t]+(\$'.self::$r_string.')\s+('.self::$r_type.')/im';
				$vars = array("type" => 2, "var" => 1);
			} else {
				if ("param"===$res[$i]) {
					$regex = '/^[ \t]+('.$r_type.')\s+(\$'.self::$r_string.')/im';
					$vars = array("type" => 1, "var" => 2);
				} elseif (("var"===$res[$i]) || ("return"===$res[$i])) {
					$regex = '/^[ \t]+('.$r_type.')/im';
					$vars = array("type" => 1);
				} else $regex = false;
			}
			
			if ($regex && preg_match($regex, $res[$i+1], $m)) {
				$res2 = array("str" => "@".$res[$i].$m[0]);
				foreach ($vars as $k=>$v)
					$res2[$k] = $m[$v];
				if ("param"===$res[$i]) {
					if (!isset($tags["params"]))
						$tags["params"] = array();
					$tags["params"][$res2["var"]] = $res2;
				} else
					$tags[$res[$i]] = $res2;
			}
			
			$i++;
		}
		
		return $tags;
	}
}

/**
 * Php парсер
 */
class DoxyphpTokenizer {
	
	protected static $psourceVarTokens = array(
		T_COMMENT,
		T_ML_COMMENT,
		T_DOC_COMMENT,
		T_CONSTANT_ENCAPSED_STRING,
		T_VARIABLE,
		T_STRING,
	);
	protected static $psourceVarTokensExclude = '/^define$/i';
	
	public static function makeTokens($source) {
		return token_get_all($source);
	}
	
	public static function makePseudoSource($tokens, $var_tokens=false, $token_exclude=false, $is_cuts_scope=true) {
		if ((false===$token_exclude) && (false===$var_tokens))
			$token_exclude = self::$psourceVarTokensExclude;
		if (false===$var_tokens)
			$var_tokens = self::$psourceVarTokens;
		
		$vars = array();
		$vars_cnt = array();
		$source = array();
		for ($i=0, $ci=sizeof($tokens); $i<$ci; $i++) {
			if (is_string($tokens[$i]))
				$source[] = $tokens[$i];
			else {
				$is_scope = false;
				if ((in_array($tokens[$i][0], $var_tokens) && (false===$token_exclude || !preg_match($token_exclude, $tokens[$i][1])))
					|| ($is_cuts_scope && ($is_scope=preg_match('/^var|const|abstract|static|public|protected|private$/i', $tokens[$i][1])))) {
					
					if ($is_scope) {
						$token = T_CONST;
						$token_name = "T_SCOPE";
					} else {
						$token = $tokens[$i][0];
						$token_name = token_name($tokens[$i][0]);
					}
					if (!isset($vars_cnt[$token]))
						$vars_cnt[$token] = 0;
					else
						$vars_cnt[$token]++;
//					$n = $token_name."__".str_pad($vars_cnt[$token], 4, "0", STR_PAD_LEFT);
					$n = $token_name."__".$vars_cnt[$token];
					$vars[$n] = $tokens[$i][1];
					$source[] = $n;
				} else
					$source[] = $tokens[$i][1];
			}
		}
		
		$res = array(
			implode("", $source),
			$vars,
		);
		
		return $res;
	}
	
	public static function makeSourceByPseudo($pseudo_source, $vars) {
		return strtr($pseudo_source, $vars);
	}
	
	public static function makeSourceByTokens($tokens) {
		$source = array();
		for ($i=0, $ci=sizeof($tokens); $i<$ci; $i++) {
			if (is_string($tokens[$i]))
				$source[] = $tokens[$i];
			else
				$source[] = $tokens[$i][1];
		}
		return implode("", $source);
	}
}
?>
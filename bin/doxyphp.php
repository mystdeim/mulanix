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
	// ���� TRUE ���������� � �� ������������������ ���������� ��� undefined
	public static $undefined = 0;
	// ���� TRUE ������� ���� � ���� ����������
	public static $baks = 1;
	// ��� "$var = " � ���� ������������� � "LN::$var = ". ���� FALSE �� ��������.
	public static $localnamespace = "LN::";
	// ������ ����� preg_replace �� ������ ���� � ����������� ��������� � ��������
	// �� ������ ���� ������ ��� �������� ������ ��������� � ����� ������ token_get_all()
	public static $replace = array(
		"patterns" => array(
			// ���� �������� �� ����� �������� (��� ���� �� �������������� ������������)
			'/abstract\s+(class)/i',
			// ������� ����� ������������ ������, ���� ����� ������ �����
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
		// �������� � tokens //////////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////
		self::$tokens = DoxyphpTokenizer::makeTokens($source);
		
		// ���� ������ ������, ���������� ��.
		self::rule1();
		
		///////////////////////////////////////////////////////////////////////////////
		// �������� � pseudosource ////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////
		list(self::$psource, self::$psourceVars) = DoxyphpTokenizer::makePseudoSource(self::$tokens);
		
		// �������� "self::" � "parent::" �� ����� �������.
		self::rule2();
		// ����������� ���� ���������� � �������� ������ �� ���� var.
		self::rule3();
		// �� �������� ������� ����������� ������������ ��� (��� return)
		//  � ���� ���������� (param)
		self::rule4();
		// �������� ZendStudio-�������� ���� ������� (var object class)
	 	//  �� ������ ����: "Type Object;" - ���� ����������� ���������� � ��.
		self::rule5();
		// ������� � ���� ���������� ����. ���� DoxyphpConfig::$baks true.
		self::rule6();
		// �������� ����������� ���������� �������� � ����� PHP (define)
	 	//  �� ����� ��: "const CONST = VALUE;"
		self::rule7();
		// ����������� � ���� ���������� ��� ������������ �������� ������� ("LN::").
		//  ���� �������� �� ����������� �� ��� ���������� � �� ����� ������������ ������.
		self::rule8();
		// �������� ��� package �� addtogroup.
	 	//  ���� ��� ��������� ���� ����������� ��� PhpDocumentor �������� �� ����� namespace �� ������.
		self::rule9();
		
		$source = DoxyphpTokenizer::makeSourceByPseudo(self::$psource, self::$psourceVars);
		return $source;
	}
	
	/**
	 * ���� ������ ������, ���������� ��.
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
	 * �������� "self::" � "parent::" �� ����� �������.
	 */
	protected static function rule2() {
		global $argv;
		// ����� ����� ���������� �������
		$regex = '/class\s+(T_STRING__\d+)(?:\s+(extends)\s+(T_STRING__\d+))?/i';
		$res = preg_split($regex, self::$psource, null, PREG_SPLIT_DELIM_CAPTURE);
		for ($i=1, $ci=sizeof($res); $i<$ci; $i++) {
			$self = self::$psourceVars[$res[$i]];
			if ("extends"==strtolower($res[$i+1])) {
				$parent = self::$psourceVars[$res[$i+2]];
				$i+=2;
			} else $parent = "parent";
			// ���� ��� T_STRING::, � ���� ��� self/parent ��������
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
	 * ����������� ���� ���������� � �������� ������ �� ���� var.
	 */
	protected static function rule3() {
		// ���� ��-�� ������
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
	 * �� �������� ������� ����������� ������������ ��� (��� return)
	 *  � ���� ���������� (param)
	 */
	protected static function rule4() {
		// ���� ������/�������
		$regex = '/(T_DOC_COMMENT__\d+|T_COMMENT__\d+)?\s*(?:T_SCOPE__\d+(?:\s+T_SCOPE__\d+)*\s+)?function\s+((\&)?\s*T_STRING__\d+)\s*\((.*?)\)\s*[\{;]/is';
		if (preg_match_all($regex, self::$psource, $m, PREG_SET_ORDER)) {
			for ($i=0, $ci=sizeof($m); $i<$ci; $i++) {
				$func = $m[$i][0];
				$vars = $tags = array();
				
				// �������� �� �������� �������� ����������
				if (!empty($m[$i][1])) {
					$docblock = self::$psourceVars[$m[$i][1]];
					$tags = DoxyphpDocblockParser::process($docblock);
					if (isset($tags["params"]))
						$vars = $tags["params"];
				}
				
				// ����������� ��� �������
				if (isset($tags["return"])) {
					$type = $tags["return"]["type"].$m[$i][3];
					// ������� � �������� ���
					$str = str_replace($type, "", $tags["return"]["str"]);
					$docblock = str_replace($tags["return"]["str"], $str, $docblock);
				} else
					$type = "void".$m[$i][3];
				$func = str_ireplace("function", "function ".$type, $func);
				if ($m[$i][3])
					$func = str_replace($m[$i][2], substr($m[$i][2], 1), $func);
				
				// �������� ��������� �������
				if (preg_match_all('/(T_STRING__\d+\s+)?(\&)?(T_VARIABLE__\d+)/', $m[$i][4], $m2, PREG_SET_ORDER)) {
					for ($j=0, $cj=sizeof($m2); $j<$cj; $j++) {
						$var = self::$psourceVars[$m2[$j][3]];
						// ��������� ��� � ������ �������
						if (empty($m2[$j][1])) {
							if (isset($vars[$var])) $type = $vars[$var]["type"].$m2[$j][2]." ";
							elseif (DoxyphpConfig::$undefined) $type = "undefined".$m2[$j][2]." ";
							else $type = $m2[$j][2];
							$func = str_replace($m2[$j][0], $type.$m2[$j][3], $func);
						}
						// ������� � �������� ���
						if (isset($vars[$var])) {
							$str = str_replace($vars[$var]["type"], "", $vars[$var]["str"]);
							if (DoxyphpConfig::$baks)
								$str = str_replace("$", "", $str);
							$docblock = str_replace($vars[$var]["str"], $str, $docblock);
						}
					}
				}
				
				// �������� �������
				if (!empty($m[$i][1]))
					self::$psourceVars[$m[$i][1]] = $docblock;
				
				// �������� ������ �������
				self::$psource = str_replace($m[$i][0], $func, self::$psource);
			}
		}
	}
	
	/**
	 * �������� ZendStudio-�������� ���� ������� (var object class)
	 *  �� ������ ����: "Type Object;" - ���� ����������� ���������� � ��.
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
	 * ������� � ���� ���������� ����. ���� DoxyphpConfig::$baks true.
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
	 * �������� ����������� ���������� �������� � ����� PHP (define)
	 *  �� ����� ��: "const CONST = VALUE;"
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
	 * ����������� � ���� ���������� ��� ������������ �������� ������� ("LN::").
	 *  ���� �������� �� ����������� �� ��� ���������� � �� ����� ������������ ������.
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
	 * �������� ��� package �� addtogroup.
	 *  ���� ��� ��������� ���� ����������� ��� PhpDocumentor �������� �� ����� namespace �� ������.
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
 * ������ ���-������.
 */
class DoxyphpDocblockParser {
	protected static
		$tags  = "var|param|return",
		$r_string = '[a-z0-9_]+',
		$r_type = '(?:[a-z0-9_]+(?:\[\])?|array[ \t]+of[ \t]+[a-z0-9_]+)';
	
	public static function process($docblock, $is_var_comment=false) {
		// ������� ����������� "/**", "/*", "*", "*/" 
		if ($is_var_comment)
			$regex = '/((^\/\*[ ]?)|([ ]?\*\/$))?/m';
		else
			$regex = '/^[ \t]*(?:(?:\/\*\*)|\*\/|\*)[ ]?/m';
		$docblock = preg_replace($regex, '', $docblock);
		
		// ��� ����� ���� ����: "type1|type2"
		$r_type = self::$r_type.'(?:\|'.self::$r_type.')*';
		
		// ��������� �� �����
		$res = preg_split('/\@('.self::$tags.')/', $docblock, null, PREG_SPLIT_DELIM_CAPTURE);
		
		// �������� ���� � ����� ��� ������� ����
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
 * Php ������
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
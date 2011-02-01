<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Template Code',
	'pi_version' => '1.0.0',
	'pi_author' => 'Rob Sanchez',
	'pi_author_url' => 'http://barrettnewton.com/',
	'pi_description' => 'Display a template without parsing its tags, and converting html entities; useful for showing off template code.',
	'pi_usage' => Template_code::usage()
);

class Template_code
{
	public $return_data = '';
	
	public function __construct()
	{
		$this->EE = get_instance();
	}
	
	public function Template_code()
	{
		if ( ! $template = $this->EE->TMPL->fetch_param('template'))
		{
			return;
		}
		
		if (strpos($template, '/') !== FALSE)
		{
			list($group_name, $template_name) = preg_split('#/#', $template);
		}
		else
		{
			$group_name = $template;
			
			$template_name = 'index';
		}
		
		$this->EE->db->select('exp_templates.template_data');
		$this->EE->db->join('exp_template_groups', 'exp_template_groups.group_id = exp_templates.group_id');
		$this->EE->db->where('exp_templates.template_name', $template_name);
		$this->EE->db->where('exp_template_groups.group_name', $group_name);
		$this->EE->db->where('exp_template_groups.site_id', $this->EE->config->item('site_id'));
		$this->EE->db->limit(1);
		
		$query = $this->EE->db->get('exp_templates');
		
		if ( ! $query->num_rows())
		{
			return;
		}
		
		$this->return_data = str_replace(array('{', '}'), array('&#123;', '&#125;'), htmlentities($query->row('template_data')));
	}
	
	public static function usage()
	{
		ob_start();
?>
{exp:template_code template="site/template"}
<?php
		$buffer = ob_get_contents();
		
		ob_end_clean(); 
	
		return $buffer;
	}
}
/* End of file pi.template_code.php */ 
/* Location: ./system/expressionengine/third_party/template_code/pi.template_code.php */ 
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Template Code',
	'pi_version' => '1.0.1',
	'pi_author' => 'Rob Sanchez',
	'pi_author_url' => 'http://github.com/rsanchez',
	'pi_description' => 'Display a template without parsing its tags, and converting html entities; useful for showing off template code.',
	'pi_usage' => Template_code::usage()
);

class Template_code
{
	public $return_data = '';
	
	public function Template_code()
	{
		$this->EE = get_instance();
		
		if ( ! $template = $this->EE->TMPL->fetch_param('template'))
		{
			return;
		}
		
		if (strpos($template, '/') !== FALSE)
		{
			list($group_name, $template_name) = explode('/', $template);
		}
		else
		{
			$group_name = $template;
			
			$template_name = 'index';
		}
		
		$this->EE->load->model('template_model');
		
		$query = $this->EE->template_model->get_templates(
			$this->EE->TMPL->fetch_param('site_id'),
			array('template_data'),
			array('template_name' => $template_name, 'group_name' => $group_name)
		);
		
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
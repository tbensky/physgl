function js_pre_error(str)
{
	console.log(str);
}

function js_prefix()
{
	var str = '';
	
	str += 'function cos(x) { return(Math.cos(x)); }\n';
	str += 'function sin(x) { return(Math.sin(x)); }\n';
	str += 'function asin(x) { return(Math.asin(x)); }\n';
	str += 'function acos(x) { return(Math.acos(x)); }\n';
	str += 'function arccos(x) { return(Math.acos(x)); }\n';
	str += 'function arcsin(x) { return(Math.asin(x)); }\n';
	str += 'function sech(x) { return(2 / (Math.exp(x) + Math.exp(-x)));}\n';
	str += 'function atan(x) { return(Math.atan(x));}\n';
	str += 'function atan2(x,y) { return(Math.atan2(x,y));}\n';
	str += 'function tan(x) { return(Math.tan(x)); }\n';
	str += 'function sqrt(x) { return(Math.sqrt(x));}\n';
	str += 'function exp(x) { return(Math.exp(x));}\n';
	str += 'function csc(x) { return(1/sin(x));}\n';
	str += 'function sec(x) { return(1/cos(x));}\n'; 
	str += 'function cot(x) { return(1/tan(x));}\n'; 
	str += 'function sinh(x) { return (Math.exp(x) - Math.exp(-x)) / 2;}\n';
	str += 'function cosh(x) { return (Math.exp(x) + Math.exp(-x)) / 2;}\n';
	str += 'function tanh(x) { return (Math.exp(x) - Math.exp(-x)) / (Math.exp(x) + Math.exp(-x));}\n';
	str += 'function abs(x) { return(Math.abs(x)); }\n';

	return(str);
}

function js_isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


function js_find_global_paren_groups(code_in)
{
	var pgroup = [];
	var pstart = [];
	var st;
	var pc = 0;
	var pstart;
	var in_sq = false;
	var in_dq = false;
	var in_comment = false;
	
	i = 0;
	while(i < code_in.length)
		{
			if (code_in[i] == '\'') 
				in_sq = !in_sq;
			if (code_in[i] == '"') 
				in_dq = !in_dq;
			if (i < code_in.length-1 && code_in[i] == '/' && code_in[i+1] == '/')
				in_comment = true;
			if (in_comment == true && (code_in[i] == '\n' || code_in[i] == '\r'))
				in_comment = false;
			if (code_in[i] == '(' && !in_sq && !in_dq)
				{
					pstart.push(i+1);
					pc++;
				}
				
			if (pc && code_in[i] == ')' && !in_sq && !in_dq)
				{
					pc--;
					st = pstart.pop();
					pgroup.push({start: st,end: i-1,len: i-st,content: code_in.substr(st,i-st)});
				}
			i++;
		}
	pgroup.sort(function(a,b) {return(a.len - b.len)});
	return(pgroup);
}

function js_split_commas(s)
{
	var in_sq = false;
	var in_dq = false;
	var in_comment = false;
	var in_vector = false;
	var i, guts, start;
	var comma_pos = [];
	var comma_guts = [];
	
	for(i=0;i<s.length;i++)
		{
			
			if (s[i] == '\'') 
				in_sq = !in_sq;
			if (s[i] == '"') 
				in_dq = !in_dq;
			if (i < s.length-1 && s[i] == '/' && s[i+1] == '/')
				in_comment = true;
			if (in_comment == true && (s[i] == '\n' || s[i] == '\r'))
				in_comment = false;
			if (s[i] == '<' && !in_sq && !in_dq && !in_comment)
				in_vector = true;
			if (s[i] == '>' && !in_sq && !in_dq && !in_comment)
				in_vector = false;
			if (s[i] == ',' && !in_sq && !in_dq && !in_vector && !in_comment)
				comma_pos.push(i);
		}
	
	if (comma_pos.length == 0)
		{
			comma_guts.push(s);
			return(comma_guts);
		}
		
	start = 0;
	for(i=0;i<comma_pos.length;i++)
		{
			guts = s.substr(start,comma_pos[i] - start);
			comma_guts.push(guts);
			start = comma_pos[i]+1;
		}
	guts = s.substr(start);
	comma_guts.push(guts);
	return(comma_guts);
}

function js_handle_parentheses(s)
{
	var s1, novect;
	var p;
	var paren_groups = [];
	var comma_guts;
	var pc = 0;
	var left, right, guts, replace;
	var i,j;
	var infix_comma;
	
	s1 = s;
	
	while(s1.indexOf('(') != -1 && s1.indexOf(')') != -1)
		{
			p = js_find_global_paren_groups(s1);
			left = s1.substr(0,p[0].start-1);
			right = s1.substr(p[0].end+2);
			guts = s1.substr(p[0].start,p[0].end-p[0].start+1);
			replace = '__paren_group__'+pc+'__';
			s1 = left + replace + right;
			paren_groups.push({key: replace,token: guts});
			pc++;
		}
		
	for(i=0;i<paren_groups.length;i++)
		{
			comma_guts = js_split_commas(paren_groups[i].token);
			infix_comma = '';
			for(j=0;j<comma_guts.length;j++)
				{
					infix_comma = infix_comma + Infix_to_Prefix(comma_guts[j]);
					if (j < comma_guts.length-1)
						infix_comma = infix_comma + ',';
				}
			paren_groups[i].token = infix_comma;
		}
		

	return({code: s1,paren_groups: paren_groups});

}

	

function js_vector_group_bailout(code_in,i)
{
	if ('<&=!|{};\n\r'.indexOf(code_in[i]) != -1)
		return(true);
	if (look_right(code_in,i,"if"))
		return(true);
	if (look_right(code_in,i,"then"))
		return(true);
	if (look_right(code_in,i,"and"))
		return(true);
	if (look_right(code_in,i,"or"))
		return(true);
	if (look_right(code_in,i,"do"))
		return(true);
	
	return(false);

}


function js_simple_replace(code_in)
{
	var in_dq = false;
	var in_sq = false;
	var i=0;
	var code_out = '';
	
	while(i < code_in.length)
		{
			
			if (code_in[i] == '\'') 
				in_sq = !in_sq;
			if (code_in[i] == '"') 
				in_dq = !in_dq;
				
			if (!in_dq && !in_sq && look_right_no_ws(code_in,i,"Pi"))
			{
				code_out += 'Math.PI';
				i += "Pi".length;
			}
			
			if (!in_dq && !in_sq && look_right_no_ws(code_in,i,"PI"))
			{
				code_out += 'Math.PI';
				i += "PI".length;
			}
			
			if (!in_dq && !in_sq && look_right(code_in,i,"and"))
				{
					code_out += ' && ';
					i += ws_length("and");
				}
				
			if (!in_dq && !in_sq && code_in[i] == '<' && code_in[i+1] == '>')
				{
					code_out += ' != ';
					i += ws_length("<>");
				}
				
			if (!in_dq && !in_sq && look_right(code_in,i,"or"))
				{
					code_out += ' || ';
					i += ws_length("or");
				}
				
			if (!in_dq && !in_sq && i < code_in.length-2 && 
							code_in[i] == '.' && 
							(code_in[i+1] == 'x' || code_in[i+1] == 'y' || code_in[i+1] == 'z') &&
							code_in[i+2].search(/[^a-z]/i) != -1)
						{
							if (code_in[i+1] == 'x')
								vi = 0;
							if (code_in[i+1] == 'y')
								vi = 1;
							if (code_in[i+1] == 'z')
								vi = 2;
							code_out += '[' + vi + ']';
							i += 2;
						}
				
			if (i < code_in.length)
		 		{
		 			code_out += code_in[i];
					i++;
				}
		}
	return(code_out);
}


function js_preprocess(code_master)
{
	var in_dq = false;
	var in_sq = false;
	var in_comment = false;
	var function_start = false;
	var in_function_args = false;
	var if_condition_start;
	var in_if_condition = false;
	var in_body_of = [];
	var in_animate_while_condition = false;
	var while_condition_start, animate_while_condition_start;
	var animate_while_condition;
	var in_while_condition = false;
	var in_for_limits = 0; //1=looking for variable,1=looking for start,2=looking for end,3=looking for step
	var for_last_start;
	var for_variable = '', for_start = '', for_end = '', for_step = '';
	var for_line, for_test;
	var in_expression = false;
	var expr, expression_start, expression_end;
	var i=0, i0, ok_to_copy;
	var code_out = '';
	var ws = ' \n\r\t';
	var ret;
	var paren_out;
	Array.prototype.last = function() { return this[this.length - 1];}
	

	paren_out = js_handle_parentheses(code_master + '\n');
	
	code_in = paren_out.code;
	
	while(i < code_in.length)
		{
			i0 = i;
			ok_to_copy = true;
			
			if (code_in[i] == '\'') 
				in_sq = !in_sq;
			if (code_in[i] == '"') 
				in_dq = !in_dq;
			if (i < code_in.length-1 && code_in[i] == '/' && code_in[i+1] == '/')
				in_comment = true;
			if (in_comment == true && (code_in[i] == '\n' || code_in[i] == '\r'))
				in_comment = false;
				
			if (!in_dq && !in_sq && !in_comment)
				{
					if (look_right(code_in,i,"if"))
						{
							if_condition_start = i + ws_length("if");
							in_if_condition = true;
							code_out += 'if ';
							i += ws_length("if");
						}
						
					if (look_right(code_in,i,"then") && in_if_condition)
						{
							in_if_condition = false;
							in_body_of.push('if');
							if_cond = code_in.substr(if_condition_start,i - if_condition_start);
							code_out += '(' + if_cond + ')\n{\n';
							i += ws_length("then");
						}
					
					if (look_right(code_in,i,'end') && (in_body_of.last() == 'if' || in_body_of.last() == 'function' || in_body_of.last() == 'while' || in_body_of.last() == 'for'))
						{
							//Be sure an in_body_of.pop(); is somewhere in this block
							code_out += '\n} //' + in_body_of.pop() + '\n';
							i += ws_length("end");
						}
						
					if (look_right(code_in,i,"end") && in_body_of.last() == 'animate')
						{
							in_body_of.pop();
							code_out += '\n if ('+animate_while_cond+') { clear(); _PHYSGL_frame_count++; _PHYSGL_in_animation_loop = true;} else { if (_PHYSGL_loop == true) run(\'small\',false); else _PHYSGL_stop_run(); }\n';
							code_out += '\n if (_PHYSGL_single_step == true) _PHYSGL_pause = true;\n';
							code_out += '}}\n_PHYSGL_frame_count = 0;\n__animate_while();\n';
						
							i += ws_length("end");
						}
				
					if (look_right(code_in,i,"function"))
						{
							function_start = true;
							code_out += 'function';
							i += ws_length("function");
						}
						
					// look for a #__ sequence, which ends a param group token
					if (i>=3 && code_in[i-1] == '_' && code_in[i-2] == '_' && js_isNumber(code_in[i-3]) && function_start == true)
						{
							code_out += '\n{\n';
							function_start = false;
							in_body_of.push('function');
							i++;
						}
						
					if (look_right(code_in,i,"for"))
						{
							for_last_start = i + ws_length("for");
							in_for_limits = 1;
							code_out += 'for ';
							i += ws_length("for");
						}
						
					
					if (code_in[i] == '=' && in_for_limits == 1)
						{
							for_variable = code_in.substr(for_last_start,i-for_last_start);
							for_last_start = i+1;
							in_for_limits++;
							i++;
						}
					
					if (code_in[i] == ',' && in_for_limits == 2)
						{
							for_start = code_in.substr(for_last_start,i-for_last_start);
							for_last_start = i+1;
							in_for_limits++;
							i++;
						}
						
					if (code_in[i] == ',' && in_for_limits == 3)
						{
							for_end = code_in.substr(for_last_start,i-for_last_start);
							for_last_start = i+1;
							in_for_limits++;
							i++;
						}
						
							
					if (look_right(code_in,i,'do') && in_for_limits >= 3)
						{
							if (in_for_limits == 3)
								for_end = code_in.substr(for_last_start,i-for_last_start);
							if (in_for_limits == 4)
								for_step = code_in.substr(for_last_start,i-for_last_start);
							//assume we count up if for_end is a variable
							
							if (parseFloat(for_start) <= parseFloat(for_end) || !js_isNumber(for_end.charAt(0)))
								{
									if (for_step == '')
										for_step = 1;
									for_test = '<=';
								}
							else
								{
									if (for_step == '')
										for_step = -1;
									for_test = '>=';
								}
								
							for_line = '('+for_variable+'='+for_start+';'+for_variable+for_test+for_end+';'+for_variable + '+=' + for_step + ')\n{\n';
							code_out += for_line;
							for_variable = for_start = for_end = for_step = '';
							in_for_limits = 0;
							in_body_of.push('for');
							i += ws_length('do');
						}
					
					
					
					if (look_right(code_in,i,"while"))
						{
							in_while_condition = true;
							i += ws_length("while");
							animate_while_condition_start = while_condition_start = i;
						}
						
					if (look_right(code_in,i,"animate") && in_while_condition)
						{
							in_while_condition = false;
							in_body_of.push('animate');
							animate_while_cond = code_in.substr(animate_while_condition_start,i - animate_while_condition_start);
							animate_while_condition_start = while_condition_start;
							i += ws_length("animate");
							code_out += '_PHYSGL_stop_request = false; function __animate_while()\n{\nif (!_PHYSGL_pause) {\n';
						}
							
					if (look_right(code_in,i,"do") && in_while_condition)
						{
							in_while_condition = false;
							in_body_of.push('while');
							while_cond = code_in.substr(while_condition_start,i - while_condition_start);
							i += ws_length("do");
							code_out += 'while ('+while_cond+')\n{\n';
						}
						
				
					if (i && i < code_in.length-1 && code_in[i] == '=' && 
							code_in[i+1] != '=' && code_in[i-1] != '=' && 
							code_in[i-1] != '+' && code_in[i-1] != '-' && code_in[i-1] != '!' && code_in[i-1] != '<' && code_in[i-1] != '>' )
						{
							in_expression = true;
							expression_start = i+1;
							i++;
							code_out += ' = ';
						}
						
					if (in_expression && (code_in[i] == '\n' || code_in[i] == '\r' || code_in[i] == ';'))
						{
							in_expression = false;
							expression_end = i-1;
							expr = code_in.substr(expression_start,expression_end - expression_start+1);
							code_out += Infix_to_Prefix(expr) + code_in[i];
							ok_to_copy = false;
						}
					
							
				}
				
		 	if (i < code_in.length)
		 		{
		 			if (!in_if_condition && !in_while_condition && !in_expression && ok_to_copy && in_for_limits == 0)
						code_out += code_in[i];
					if (i0 == i)
						i++;
				}
		}
		
	for(i=paren_out.paren_groups.length-1;i>=0;i--)
		code_out = code_out.replace(paren_out.paren_groups[i].key,'('+paren_out.paren_groups[i].token+')');
		
	console.log(code_out);
	code_out = js_simple_replace(code_out);
	return(js_prefix() + code_out);
	
}

function ws_length(str)
{
	return(str.length);
}

function look_right(str,offset,token)
{
	var left_ws, right_ws;
	var left_c, right_c;
	
	if (offset == 0)
		left_ws = true;
	else 
		{
			left_c = str.substr(offset-1,1);
			left_ws = " \n\r\t".indexOf(left_c) != -1;
		}
	
	if (offset == str.length-1)
		right_ws = true;
	else 
		{
			right_c = str.substr(offset+token.length,1);
			right_ws =  " \n\r\t".indexOf(right_c) != -1;
		}
	
	return(str.substr(offset,token.length) == token && left_ws && right_ws);
}


function look_right_no_ws(str,offset,token)
{
	return(str.substr(offset,token.length) == token);
}


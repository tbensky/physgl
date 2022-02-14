function _PHYSGL_error_message(str)
{
	$('#error_message').html(str);
}

function _PHYSGL_paren_balance(code)
{
	var dq = false, sq = false, pc = 0;
	var in_comment = false;
	var i;
	
	for(i=0;i<code.length;i++)
		{
			if (code[i] == '\'')
				sq = !sq;
			if (code[i] == '"')
				dq = !dq;
			if (i < code.length-1 && code[i] == '/' && code[i+1] == '/')
				in_comment = true;
			if (in_comment == true && (code[i] == '\n' || code[i] == '\r'))
				in_comment = false;
			if (!sq && !dq && !in_comment && code[i] == '(')
				pc++;
			if (!sq && !dq && !in_comment && code[i] == ')')
				pc--;
		}
		
	return(pc == 0);
}

function _PHYSGL_paren_balance_line_by_line(code)
{
	var lines = code.split("\n");
	var i;
	
	for(i=0;i<lines.length;i++)
		{
			if (!_PHYSGL_paren_balance(lines[i]))
				return('Parentheses do not balance in line '+(i+1));
		}
	return('none');
}

function _PHYSGL_error_check(code)
{
	var ret;
	
	ret = _PHYSGL_paren_balance_line_by_line(code);
	if (ret != 'none')
		return(ret);
	
	ret = _PHYSGL_count_control_structures(code);
	if (ret != 'none')
		return(ret);
	
		
	return('none');
}

function _PHYSGL_count_control_structures(code)
{
	var cwhile, cfor, cdo, canimate, cend, cif, cthen, cfunction;
	var i;
	
	cwhile = cfor = cdo = canimate = cend = cif = cthen = cfunction = 0;
	for(i=0;i<code.length;i++)
		{
			if (_PHYSGL_look_right(code,i,"while"))
				cwhile++;
			if (_PHYSGL_look_right(code,i,"function"))
				cfunction++;
			if (_PHYSGL_look_right(code,i,"then"))
				cthen++;
			if (_PHYSGL_look_right(code,i,"if"))
				cif++;
			if (_PHYSGL_look_right(code,i,"for"))
				cfor++;
			if (_PHYSGL_look_right(code,i,"do") || _PHYSGL_look_right(code,i,"animate"))
				cdo++;
			if (_PHYSGL_look_right(code,i,"end"))
				cend++;
		}
	if (cwhile + cfor != cdo)
		return('Every "for" or "while" keyword must be followed by a "do" keyword.');
	if (cfunction + cif + cwhile + cfor != cend)
		return('Every "if" and "function" keyword, and "for" or "while" loop must be terminated with an "end" statement.');
	if (cif != cthen)
		return('Every "if" keyword must be followed a "then" keyword.');
	return('none');
}
			
			
		

function _PHYSGL_look_right(str,offset,token)
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

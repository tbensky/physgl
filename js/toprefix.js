function ToPrefix_is_operator(c)
{
	return('/*+-^<>='.indexOf(c) != -1);
}

function ToPrefix_is_white(c)
{
	return('\n\r\t '.indexOf(c) != -1);
}

//return left and right operands, given that
//an operator is at position op_pos in expr
function ToPrefix_get_operands(expr,op_pos)
{
	var i;
	var left, right;
	var expr_start;
	var expr_end;
	
	if (op_pos == 0)
		{
			var left = '';
			var expr_start = 0;
		}
	else
		{
			i = op_pos-1;
			while(i>=0 && !ToPrefix_is_operator(expr[i]))
				{
					i--;
				}
			left = expr.substr(i+1,op_pos-1- i);
			expr_start = i+1;
		}
		
	if (op_pos == expr.length-1)
		{
			var right = '';
			var expr_start = 0;
		}
	else
		{
			i = op_pos+1;
			while(i < expr.length && !ToPrefix_is_operator(expr[i]))
				{
					i++;
				}
			right = expr.substr(op_pos+1,i-1-op_pos);
			expr_end = i-1;
		}
	left = left.trim(); 
	right = right.trim(); 
	return({left: left,right: right, start: expr_start, end: expr_end});	
}


function ToPrefix_isScalar(n)
{
	return(typeof n == 'number');
}

function ToPrefix_isVector(n)
{
	if (typeof n == 'object')
		return(n.length == 3);
	return(false);
}
	
//replace the chars between start and end in str with new_str
function ToPrefix_replace_chunk(str,start,end,new_str)
{
	var left = str.substr(0,start);
	var right = str.substr(end+1);
	return(left + new_str + right);
}

//return the prefix form of infix expression expr
function ToPrefix_infix_to_prefix(expr)
{
	var i=0;
	var out = expr;
	var ret;
	var sq, dq;
	
	
	sq = dq = false;
	while(i < out.length)
	{
		if (out[i] == '\'') sq = !sq;
		if (out[i] == '"') dq = !dq;
		if (i < out.length-1 && out[i] == '/' && out[i+1] == '/')
			break;
			
		if (out[i] == '^' && !dq && !sq)
			{
				ret = ToPrefix_get_operands(out,i);
				out = ToPrefix_replace_chunk(out,ret.start,ret.end,'ToPrefix_power('+ret.left+','+ret.right+')');
				i = 0;
			}
		else i++;
	}
	
	i = 0;
	sq = dq = false;
	while(i < out.length)
	{
		if (out[i] == '\'') sq = !sq;
		if (out[i] == '"') dq = !dq;
		if (i < out.length-1 && out[i] == '/' && out[i+1] == '/')
			break;
		
		if ((out[i] == '*' || out[i] == '/') && (!sq && !dq))
			{
				ret = ToPrefix_get_operands(out,i);
				if (out[i] == '*')
					out = ToPrefix_replace_chunk(out,ret.start,ret.end,'ToPrefix_multiply('+ret.left+','+ret.right+')');
				else out = ToPrefix_replace_chunk(out,ret.start,ret.end,'ToPrefix_divide('+ret.left+','+ret.right+')');
				i = 0;
			}
		else i++;
	}
	
	
	
	i = 0;
	sq = dq = false;
	while(i < out.length)
	{
		if (out[i] =='\'') sq = !sq;
		if (out[i] == '"') dq = !dq;
		if (i < out.length-1 && out[i] == '/' && out[i+1] == '/')
			break;
		if ((out[i] == '+' || out[i] == '-') && (!sq && !dq))
			{
				ret = ToPrefix_get_operands(out,i);
				if (ret.left == '')
					{
						switch(out[i])
							{
								case '+': out = ToPrefix_replace_chunk(out,ret.start,ret.end,'ToPrefix_number(' + ret.right + ')'); break;
								case '-': out = ToPrefix_replace_chunk(out,ret.start,ret.end,'ToPrefix_neg_number(' +ret.right + ')'); break;
							}
					}
				else if (out[i] == '+')
						out = ToPrefix_replace_chunk(out,ret.start,ret.end,'ToPrefix_add('+ret.left+','+ret.right+')');
				else out = ToPrefix_replace_chunk(out,ret.start,ret.end,'ToPrefix_subtract('+ret.left+','+ret.right+')');
				i = 0;
			}
		else i++;
	}
	
	return(out);
}


function ToPrefix_neg_number(x)
{
	if (ToPrefix_isScalar(x))
		return(-x);
	else return([-x[0],-x[1],-x[2]]);
}

function ToPrefix_number(x)
{
	return(x);
}

function ToPrefix_number(x)
{
}

function ToPrefix_power(x,y)
{
	if (ToPrefix_isScalar(x) && ToPrefix_isScalar(y))
		return(Math.pow(x,y));
	if (ToPrefix_isVector(x) && y == '2')
		return(x[0] * x[0] + x[1] * x[1] + x[2] * x[2]);
	if (ToPrefix_isVector(x)) x = '&lt;'+x+'&gt;';
	if (ToPrefix_isVector(y)) y = '&lt;'+y+'&gt;';
	_PHYSGL_error_message('You cannot do '+x+'<sup>'+y+'</sup>. Exponents may only be taken of scalars.');
	return({result: 'Exponents may only be taken of scalars.', type: 'error'});
}

function ToPrefix_add(x,y)
{
	if (ToPrefix_isScalar(x) && ToPrefix_isScalar(y))
		return(x+y);
	if (ToPrefix_isVector(x) && ToPrefix_isVector(y))
		return([x[0]+y[0],x[1]+y[1],x[2]+y[2]]);
	if (ToPrefix_isVector(x)) x = '&lt;'+x+'&gt;';
	if (ToPrefix_isVector(y)) y = '&lt;'+y+'&gt;';
	_PHYSGL_error_message('You cannot add a vector and a scalar in '+x+' and ' + y+'.');
	return({result: 'You cannot add a vector and a scalar in '+x+' and ' + y+'.',type: 'error'});
}

function ToPrefix_subtract(x,y)
{
	if (ToPrefix_isScalar(x) && ToPrefix_isScalar(y))
		return(x-y);
	if (ToPrefix_isVector(x) && ToPrefix_isVector(y))
		return([x[0]-y[0],x[1]-y[1],x[2]-y[2]]);
	if (ToPrefix_isVector(x)) x = '&lt;'+x+'&gt;';
	if (ToPrefix_isVector(y)) y = '&lt;'+y+'&gt;';
	_PHYSGL_error_message('You cannot subtract a vector and a scalar.');
	return({result: 'You cannot subtract a vector and a scalar.',type: 'error'});
}

function ToPrefix_multiply(x,y)
{
	if (ToPrefix_isScalar(x) && ToPrefix_isScalar(y))
		return(x*y);
	if (ToPrefix_isVector(x) && ToPrefix_isScalar(y))
		return([x[0]*y,x[1]*y,x[2]*y]);
	if (ToPrefix_isScalar(x) && ToPrefix_isVector(y))
		return([y[0]*x,y[1]*x,y[2]*x]);
	if (ToPrefix_isVector(x) && ToPrefix_isVector(y))
		return(x[0]*y[0]+x[1]*y[1]+x[2]*y[2]);
	if (ToPrefix_isVector(x)) x = '&lt;'+x+'&gt;';
	if (ToPrefix_isVector(y)) y = '&lt;'+y+'&gt;';
	_PHYSGL_error_message('You cannot mutiply '+x+' and '+y+' as shown.');
	return({result: 'You cannot mutiply '+x+' and '+y+' as shown.',type: 'error'});
}

function ToPrefix_divide(x,y)
{
	if (ToPrefix_isScalar(x) && ToPrefix_isScalar(y))
		return(x/y);
	if (ToPrefix_isVector(x) && ToPrefix_isScalar(y))
		return([x[0]/y,x[1]/y,x[2]/y]);
	if (ToPrefix_isScalar(x) && ToPrefix_isVector(y))
		_PHYSGL_error_message('You cannot divide a scalar by a vector as in '+x+' / &lt;'+y+'&gt;');
	if (ToPrefix_isVector(x) && ToPrefix_isVector(y))
		_PHYSGL_error_message('You cannot divide two vectors as in &lt;'+x+'&gt; / &lt;'+y+'&gt;');
	return({result: 'You cannot divide '+x+' and '+y+' as shown.',type: 'error'});
}

//see if a function name exists to the left of an open (
function ToPrefix_see_if_func(str,index)
{
	if (ToPrefix_is_operator(str[index-1]) || ToPrefix_is_white(str[index-1]))
		return('');
	var i = index-1;
	while(i>=0 && !ToPrefix_is_operator(str[i]) && !ToPrefix_is_white(str[i]))
		i--;
	return(str.substr(i+1,index-1-i));
}

function ToPrefix_token_vectors(input)
{
	var in_vector;
	var open, close;
	var len;
	var open_pos;
	var str;
	var vect;
	var i;
	var dl;
	var key;
	var comma_count;
	
	
	str = input.expr;
	len = str.length;
	vect = input.vect;
	in_vector = false;
	if (str.indexOf('<') == -1 || str.indexOf('>') == -1)
		return({expr: str, vect: vect});
		
	for(i=0;i<str.length;i++)
		{
			if (i < str.length-1 && str[i] == '/' && str[i+1] == '/')
				break;
				
			if (str[i] == '<' && str[i+1] != '>' && str[i+1] != '=' && in_vector == false)
				{
					in_vector = true;
					open_pos = i;
					comma_count = 0;
				}
			if (str[i] == '<' && str[i+1] != '>' && in_vector == true && i > open_pos)
				in_vector = false;
			
			if (in_vector == true && str[i] == ',')
				comma_count++;
				
			if (in_vector && comma_count == 2 && str[i] == '>')
				{
					in_vector = false;
					dl = i - open_pos;
					if (dl < len)
						{
							open = open_pos;
							close = i;
							len = dl;
						}
				}
		}
	key = '__vect'+vect.length;
	vect.push(str.substr(open+1,close-open-1));
	str = ToPrefix_replace_chunk(str,open,close,key);
	input = ToPrefix_token_vectors({expr: str,vect: vect});
	return({expr: input.expr, vect: input.vect});
}

	
//ID and tokenize all parenthesis groups in string input. More:
// find all ( ) groups in the expression and tokenize
// them into paren0, paren1, etc. Values of paren[n]
// are returned in the paren key. The paren[n] values
// do not have any parenthesis in them. If the paren[n] group
// contained the arguments to a function, the function name
// is appended to the paren[n] expression after a comma.
// The tokenized expression is returned in expr (it won't 
// have any parethesis groups in it).
function ToPrefix_token_paren(input)
{
	var pc = 0;
	var open, close;
	var len;
	var open_pos;
	var str;
	var paren;
	var i;
	var dl;
	var f;
	var key;
	var dq, sq;
	
	str = input.expr;
	len = str.length;
	paren = input.paren;
	
	if (str.indexOf('(') == -1 && str.indexOf(')') == -1)
		return({expr: str, paren: paren});
		
	open = close = -1;
	dq = sq = false;
	for(i=0;i<str.length;i++)
		{
			if (str[i] == "'")
				sq = !sq;
			if (str[i] == '"')
				dq = !dq;
			if (i < str.length-1 && str[i] == '/' && str[i+1] == '/')
				break;
			
			if (!dq && !sq)
				{
					if (str[i] == '(')
						{
							pc++;
							open_pos = i;
						}
					if (pc && str[i] == ')')
						{
							pc--;
							dl = i - open_pos;
							if (dl < len)
								{
									open = open_pos;
									close = i;
									len = dl;
								}
						}
				}
		}
	if (open == -1 && close == -1)
		return({expr: str, paren: paren});
		
	key = '__paren'+paren.length;
	f = ToPrefix_see_if_func(str,open);
	paren.push(str.substr(open+1,close-open-1)+','+f);
	str = ToPrefix_replace_chunk(str,open-f.length,close,key);
	input = ToPrefix_token_paren({expr: str,paren: paren});
	return({expr: input.expr, paren: input.paren});
}


function Infix_to_Prefix(s)
{
	var main_paren, main_vect;
	var paren;
	var main_prefix;
	var pair;
	var expr;
	var func;
	var expr_infix;
	var key;
	var replacer;
	var i;
	var parts;


	main_vect = ToPrefix_token_vectors({expr: s,vect: []});
	main_paren = ToPrefix_token_paren({expr: main_vect.expr,paren: []});
	
	paren = main_paren.paren;
	//convert the parenthesis-less expression into prefix 
	main_prefix = ToPrefix_infix_to_prefix(main_paren.expr);
	for(i=0;i<paren.length;i++)
		{
			//get the expression and maybe a function name
			pair = paren[i].split(',');
			expr = pair[0];
			func = pair[1];
			//convert the expression to prefix
			expr_infix = ToPrefix_infix_to_prefix(expr);
			//figure out what to replace paren0, paren1, etc. with
			if (func.length)
				replacer = func + '(' + expr_infix + ')';
			else replacer = expr_infix;
			key = '__paren'+i;
			//do the replacement in the main expression
			main_prefix = main_prefix.replace(key,replacer);
		}
	
	
	for(i=0;i<main_vect.vect.length;i++)
		{
			key = '__vect'+i;
			parts = main_vect.vect[i].split(",");
			parts[0] = Infix_to_Prefix(parts[0]);
			parts[1] = Infix_to_Prefix(parts[1]);
			parts[2] = Infix_to_Prefix(parts[2]);
			main_vect.vect[i] = parts.join(",");
			main_prefix = main_prefix.replace(key,'['+main_vect.vect[i]+']');
		}
	
	return(main_prefix);
}

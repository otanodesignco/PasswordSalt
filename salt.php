<?php
class Salt
{
    // internal variables
    private $pool;
    private $alpha;
    private $num;
    private $char;
    private $len;
    private $type;
    private $generated;
    private $salt;
	private $types;
   
    const DEFAULT_SIZE = 5;
    const DEFAULT_TYPE = 'MIXED';

    public function __construct($size = "")
    {
        // fill pool with a mixture of alpha numeric special symbol's to cover a mixed range of salts
        $this->pool = 'a0`Ab1~Bc2!Cd3@De4#Ef5$Fg6%Gh7^Hi8&Ij9*Jk(Kl)Lm-Mn_No+Op=Pq[Qr{Rs]St}Tu|Uv\Vw: Wx;Xy"Yz\'Z<>,.?/';
        // fill alpha with upper and lower case letters
        $this->alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // fill num with numbers
        $this->num = '0123456789';
        // fill char with special characters
        $this->char = '~!@#$%^&*()_+-=`{}:"|<>?[]\;\'/.,';
        // set flag for generate method to false, object just created
        $this->generated = FALSE; // always boolean
        // set salt as a string
        $this->salt = "";
        $this->type = self::DEFAULT_TYPE;
		$this->types = array('MIXED','ALPHA','NUM','CHAR');

        // set default size, if not def constant of 5 will be used
        if( !empty( $size ) )
        {
            // $size is empty or not set so it uses the default value
             $this->len = ( ( is_numeric( $size ) ) ? $size : self::DEFAULT_SIZE );
        }
        else
        {
             $this->len = self::DEFAULT_SIZE;
        }

    }

    // php getter / setter method for the length of the salt
    public function Length($num = "")
    {
        if( !empty( $num ) )
        {
            // $num has a value this becomes a setter
            $this->len = ( ( is_numeric( $num ) ) ? $num : self::DEFAULT_SIZE );
        }
        else
        {
            // becomes a getter, returns a value
            return $this->len;
        }
    }

    // php getter / setter for the type of salt wanted
    public function Type($val = "")
    {
        if( empty( $val ) )
        {
            // value is not empty set default here
            
			return $this->type;
        }
        else
        {
            // use default value
            $this->type = ( in_array($val , $this->types ) ? $val : self::DEFAULT_TYPE );
        }
    }

    // clear salt

    public function Clear()
    {
        $this->salt = "";
    }

    /* these methods are specific to each  generator type */

    public function GenerateAlpha( $bounds = '' )
    {
        $size = strlen( $this->alpha ) / 2;

        if($bounds == strtoupper( 'UPPER' ) )
        {
            
			$this->Generator( $this->alpha, $size );
        }
		else if ( $bounds == strtoupper( 'LOWER' ) )
		{
			$this->Generator( $this->alpha, 0, $size );
		}
		else
		{
			$this->Generator( $this->alpha );
		}
		
        $this->generated = TRUE;

        return $this->salt;
    }

    public function GenerateNum()
    {
        $this->Generator( $this->num );
        $this->generated = TRUE;

        return $this->salt;
    }

    public function GenerateChar()
    {
        $this->Generator( $this->char );
        $this->generated = TRUE;

        return $this->salt;
    }

    public function GenerateMixed()
    {
        $this->Generator( $this->pool );
        $this->generated = TRUE;

        return $this->salt;
    }

    public function Generate($regenerate = true)
    {
        $regen = TRUE;

        switch( $regenerate )
        {
            case TRUE:
                $regen = TRUE;
            break;

            case 1:
                $regen = TRUE;
            break;

            case FALSE:
                $regen = FALSE;
            break;
                
            case 0:
                $regen = FALSE;
            break;
                
            default:
                $regen = TRUE;
            break;
        }

        if( $generated && !$regen )
        {
            return $this->salt;
        }
        else if( $generated && $regen)
        {
            switch( $this->type )
            {
                case 'ALPHA':
                    $this->Generator($this->alpha);
                break;

                case 'NUM':
                    $this->Generator($this->num);
                break;

                case 'CHAR':
                    $this->Generator($this->char);
                break;

                case 'MIXED':
                    $this->Generator($this->pool);
                break;

                default:
                    $this->Generator($this->pool);
                break;
            }

            $this->generated = TRUE;
        }
        else
        {
            switch( $this->type )
            {
                case 'ALPHA':
                    $this->Generator($this->alpha);
                break;

                case 'NUM':
                    $this->Generator($this->num);
                break;

                case 'CHAR':
                    $this->Generator($this->char);
                break;

                case 'MIXED':
                    $this->Generator($this->pool);
                break;

                default:
                    $this->Generator($this->pool);
                break;
            }

            $this->generated = TRUE;
        }

    }


    /* generator method coded onceto save on resources */

    private function Generator( $txt, $low = 0, $high = 0 )
    {
        $i = 0;
        $size = strlen( $txt );
		
		$high = ( ( $high == 0 ) ? $size - 1 : $high );

        for( $i = 0; $i < $this->len; $i++ )
        {
            $this->salt .= $txt{mt_rand( $low, $high )};
        } 
    }
}
?>

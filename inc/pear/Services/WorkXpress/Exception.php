<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Classes for all exceptions thrown by Services_WorkXpress
 *
 * PHP version 5
 *
 * <LICENSE>
 * Copyright (c) 2005-2006, Express Dynamics
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * - Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * - Neither the name Express Dynamics nor the names of its contributors may be
 *   used to endorse or promote products derived from this software without
 *   specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * </LICENSE>
 * 
 * @package Services_WorkXpress
 * @author  Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link    PACKAGE_URL
 */

/**
 * Base class for all exceptions thrown by Services_WorkXpress
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Exception extends PEAR_Exception
{
}

/**
 * Class for throwing exceptions when an error is raised with the WorkXpress API
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 */
class Services_WorkXpress_API_Exception extends Services_WorkXpress_Exception
{
}

?>

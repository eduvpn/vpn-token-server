<?php
/**
 *  Copyright (C) 2017 SURFnet.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SURFnet\VPN\Token;

class Template implements TplInterface
{
    private $templateDir;

    public function __construct($templateDir)
    {
        $this->templateDir = $templateDir;
    }

    /**
     * Render the template.
     *
     * @param string $templateName      the name of the template
     * @param array  $templateVariables the variables to be used in the
     *                                  template
     *
     * @return string the rendered template
     */
    public function render($templateName, array $templateVariables)
    {
        $templateFile = sprintf('%s/%s.php', $this->templateDir, $templateName);
        $templateData = require $templateFile;
        $templateKeys = array_keys($templateVariables);
        $templateValues = array_values($templateVariables);
        $preparedTemplateKeys = [];
        foreach ($templateKeys as $templateKey) {
            $preparedTemplateKeys[] = sprintf('{{ %s }}', $templateKey);
        }
        $preparedTemplateValues = [];
        foreach ($templateValues as $templateValue) {
            $preparedTemplateValues[] = htmlspecialchars((string) $templateValue, ENT_QUOTES, 'UTF-8');
        }

        return str_replace($preparedTemplateKeys, $preparedTemplateValues, $templateData);
    }
}

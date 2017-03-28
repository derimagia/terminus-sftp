<?php

namespace Pantheon\TerminusSFTP\Commands;

use Pantheon\Terminus\Commands\TerminusCommand;
use Pantheon\Terminus\Site\SiteAwareInterface;
use Pantheon\Terminus\Site\SiteAwareTrait;
use Pantheon\Terminus\Exceptions\TerminusException;
use Pantheon\Terminus\Models\Environment;
use Pantheon\Terminus\Models\Site;

/**
 * Open Site in your Default SFTP Program.
 */
class SFTPCommand extends TerminusCommand Implements SiteAwareInterface {
  use SiteAwareTrait;

  /**
   * Open SFTP
   *
   * @authorize
   *
   * @command site:sftp
   *
   * @param string $site_env Site & environment in the format `site-name.env`
   *
   * @usage terminus site:sftp <site>.<env> <app>
   *
   * @throws TerminusException
   */
  public function sftp($site_env, array $options = ['app' => NULL]) {
    /* @var $env Environment */
    /* @var $site Site */
    list($site, $env) = $this->getSiteEnv($site_env);

    $uri = $env->connectionInfo();

    $this->log()
      ->notice('Opening {site} in sftp app.', [
        'site' => $env->id . '-' . $site->get('name') . '.pantheon.io'
      ]);

    $this->execCommand('open', ['-F', $uri['sftp_url']]);
  }


  /**
   * Execute Command
   * @param $command
   * @param array|string $arguments
   * @return bool true
   *    If command executes without an error
   */
  protected function execCommand($command, $arguments = '') {
    $arguments = is_array($arguments) ? $arguments : (array) $arguments;

    if (!empty($arguments)) {
      $command .= ' ' . implode(' ', $arguments);
    }
    $this->logger->debug('Executing: {command}', ['command' => $command]);
    return exec($command, $output, $error_code);
  }

}
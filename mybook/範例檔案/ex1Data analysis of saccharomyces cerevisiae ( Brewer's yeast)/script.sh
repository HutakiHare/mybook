#!/bin/bash

# Check if Snakemake is installed, if not, install it
if ! command -v snakemake &> /dev/null
then
	echo "Snakemake not found, installing..."
	# conda install -n base -c conda-forge mamba
	conda activate base
	mamba create -c conda-forge -c bioconda --name snakemake snakemake snakedeploy -y
	# mamba create -c conda-forge -c bioconda -n snakemake snakemake
fi

# Initialize conda in the script
source $(conda info --base)/etc/profile.d/conda.sh

conda activate snakemake

# Run the Snakemake workflow
snakemake  --cores all --use-conda